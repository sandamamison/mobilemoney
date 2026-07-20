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
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['type_operation_id', 'montant_min', 'montant_max', 'frais', 'actif'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    public function getFrais(string $typeCode, int $montant): int
    {
        $sql = "SELECT bf.frais FROM baremes_frais bf
                JOIN types_operations t ON t.id = bf.type_operation_id
                WHERE t.code = ? AND bf.montant_min <= ? AND bf.montant_max >= ? AND bf.actif = 1
                LIMIT 1";

        $row = $this->db->query($sql, [$typeCode, $montant, $montant])->getRow();

        return $row ? (int) $row->frais : 0;
    }
}
