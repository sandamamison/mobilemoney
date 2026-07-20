<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operations';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['reference', 'type_operation_id', 'compte_source_id', 'compte_destination_id', 'montant', 'frais', 'statut', 'date_operation'];
    protected $useTimestamps = false;

    /**
     * Générer une référence unique
     */
    public static function generateReference(): string
    {
        return 'OP-' . date('YmdHis') . '-' . str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Récupérer l'historique d'un compte
     */
    public function getHistoriqueCompte(int $compteId, int $limit = 10): array
    {
        return $this->select('operations.*, types_operations.libelle')
            ->join('types_operations', 'operations.type_operation_id = types_operations.id')
            ->groupStart()
            ->where('compte_source_id', $compteId)
            ->orWhere('compte_destination_id', $compteId)
            ->groupEnd()
            ->where('statut', 'VALIDEE')
            ->orderBy('operations.date_operation', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Récupérer une opération par sa référence
     */
    public function getByReference(string $reference): ?array
    {
        return $this->where('reference', $reference)->first();
    }

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    public function gainsParType(): array
    {
        $sql = "SELECT code, libelle, nombre_operations, gain_total
                FROM vue_gains_operateur
                ORDER BY CASE code
                    WHEN 'RETRAIT' THEN 1
                    WHEN 'TRANSFERT' THEN 2
                    ELSE 3
                END, libelle";

        return $this->db->query($sql)->getResultArray();
    }

    public function totalgain()
    {
        $sql = 'SELECT COALESCE(SUM(gain_total), 0) AS total FROM vue_gains_operateur';
        $query = $this->db->query($sql);

        return (int) ($query->getRow()->total ?? 0);
    }

    /**
     * Gains sur les retraits uniquement.
     */
    public function gainsRetraits(): array
    {
        $sql = "SELECT
                    COUNT(o.id) AS nombre_operations,
                    COALESCE(SUM(o.frais), 0) AS gain_total
                FROM operations o
                JOIN types_operations t ON t.id = o.type_operation_id
                WHERE t.code = 'RETRAIT'
                AND o.statut = 'VALIDEE'";
        $row = $this->db->query($sql)->getRow();
        return [
            'nombre_operations' => (int) ($row->nombre_operations ?? 0),
            'gain_total'        => (int) ($row->gain_total ?? 0),
        ];
    }

    /**
     * Gains sur les transferts INTERNES (destinataire sur notre réseau).
     * Un transfert est interne si compte_destination_id IS NOT NULL et
     * que le préfixe du destinataire n'est PAS dans prefixes_autres_operateurs.
     */
    public function gainsTransfertsInternes(): array
    {
        $sql = "SELECT
                    COUNT(o.id) AS nombre_operations,
                    COALESCE(SUM(o.frais), 0) AS gain_total
                FROM operations o
                JOIN types_operations t ON t.id = o.type_operation_id
                JOIN comptes cdest ON cdest.id = o.compte_destination_id
                JOIN clients cl ON cl.id = cdest.client_id
                WHERE t.code = 'TRANSFERT'
                AND o.statut = 'VALIDEE'
                AND o.compte_destination_id IS NOT NULL
                AND NOT EXISTS (
                    SELECT 1 FROM prefixes_autres_operateurs pao
                    WHERE pao.actif = 1
                    AND SUBSTR(cl.telephone, 1, LENGTH(pao.prefixe)) = pao.prefixe
                )";
        $row = $this->db->query($sql)->getRow();
        return [
            'nombre_operations' => (int) ($row->nombre_operations ?? 0),
            'gain_total'        => (int) ($row->gain_total ?? 0),
        ];
    }

    /**
     * Gains sur les transferts EXTERNES groupés par opérateur.
     * Un transfert est externe si le préfixe du destinataire est dans prefixes_autres_operateurs.
     * La commission de l'opérateur est déduite du gain net.
     */
    public function gainsTransfertsExternesParOperateur(): array
    {
        $sql = "SELECT
                    ao.id AS operateur_id,
                    ao.nom AS operateur_nom,
                    ao.commission,
                    COUNT(o.id) AS nombre_operations,
                    COALESCE(SUM(o.montant), 0) AS montant_total,
                    COALESCE(SUM(o.frais), 0) AS frais_total,
                    COALESCE(SUM(o.frais), 0) - ROUND(
                        COALESCE(SUM(o.montant), 0) * ao.commission / 100.0
                    ) AS gain_net
                FROM operations o
                JOIN types_operations t ON t.id = o.type_operation_id
                JOIN comptes cdest ON cdest.id = o.compte_destination_id
                JOIN clients cl ON cl.id = cdest.client_id
                JOIN prefixes_autres_operateurs pao
                    ON pao.actif = 1
                    AND SUBSTR(cl.telephone, 1, LENGTH(pao.prefixe)) = pao.prefixe
                JOIN autres_operateurs ao ON ao.id = pao.autre_operateur_id AND ao.actif = 1
                WHERE t.code = 'TRANSFERT'
                AND o.statut = 'VALIDEE'
                AND o.compte_destination_id IS NOT NULL
                GROUP BY ao.id, ao.nom, ao.commission
                ORDER BY ao.nom ASC";
        return $this->db->query($sql)->getResultArray();
    }

    /**
     * Total des gains nets sur transferts externes (après déduction des commissions).
     */
    public function gainNetTransfertsExternes(): int
    {
        $lignes = $this->gainsTransfertsExternesParOperateur();
        return (int) array_sum(array_column($lignes, 'gain_net'));
    }

    /**
     * Montants à envoyer à chaque opérateur externe.
     *
     * Pour chaque opérateur :
     *   - montant_total    = somme des montants transférés (ce qu'on doit envoyer)
     *   - commission        = montant_total × (% commission)
     *   - frais_retrait     = 0 (il n'y a pas de frais de retrait pour les autres opérateurs)
     *   - total_a_regler    = montant_total + commission
     */
    public function montantsAEnvoyerParOperateur(): array
    {
        $sql = "SELECT
                    ao.id AS operateur_id,
                    ao.nom AS operateur_nom,
                    ao.commission AS taux_commission,
                    COUNT(o.id) AS nombre_operations,
                    COALESCE(SUM(o.montant), 0) AS montant_total,
                    ROUND(
                        COALESCE(SUM(o.montant), 0) * ao.commission / 100.0
                    ) AS commission_due,
                    COALESCE(SUM(o.montant), 0) + ROUND(
                        COALESCE(SUM(o.montant), 0) * ao.commission / 100.0
                    ) AS total_a_regler
                FROM operations o
                JOIN types_operations t ON t.id = o.type_operation_id
                JOIN comptes cdest ON cdest.id = o.compte_destination_id
                JOIN clients cl ON cl.id = cdest.client_id
                JOIN prefixes_autres_operateurs pao
                    ON pao.actif = 1
                    AND SUBSTR(cl.telephone, 1, LENGTH(pao.prefixe)) = pao.prefixe
                JOIN autres_operateurs ao ON ao.id = pao.autre_operateur_id AND ao.actif = 1
                WHERE t.code = 'TRANSFERT'
                AND o.statut = 'VALIDEE'
                AND o.compte_destination_id IS NOT NULL
                GROUP BY ao.id, ao.nom, ao.commission
                ORDER BY ao.nom ASC";
        return $this->db->query($sql)->getResultArray();
    }

}
