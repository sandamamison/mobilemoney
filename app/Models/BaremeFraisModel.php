<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeFraisModel extends Model
{
    protected $table            = 'baremes_frais';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'type_operation_id',
        'montant_min',
        'montant_max',
        'frais',
        'actif',
    ];
    protected $useTimestamps = false;

    /**
     * Récupérer les frais pour un montant et un type d'opération
     */
    public function getFraisForAmount(int $typeOperationId, int $amount): int
    {
        $bareme = $this->where('type_operation_id', $typeOperationId)
            ->where('montant_min <=', $amount)
            ->where('montant_max >=', $amount)
            ->where('actif', 1)
            ->first();

        return $bareme ? (int) $bareme['frais'] : 0;
    }

    /**
     * Récupérer tous les barèmes pour un type d'opération
     */
    public function getByTypeOperation(int $typeOperationId): array
    {
        return $this->where('type_operation_id', $typeOperationId)
            ->where('actif', 1)
            ->orderBy('montant_min', 'ASC')
            ->findAll();
    }
}
