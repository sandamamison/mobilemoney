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
}
