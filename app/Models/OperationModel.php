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
    protected $allowedFields    = [
        'reference',
        'type_operation_id',
        'compte_source_id',
        'compte_destination_id',
        'montant',
        'frais',
        'statut',
        'date_operation',
        'destinataire_telephone',
        'operateur_destination',
        'transfert_externe',
        'frais_transfert',
        'commission_externe',
        'frais_retrait_inclus',
        'groupe_reference',
    ];
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

}
