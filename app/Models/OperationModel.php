<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['reference', 'type_operation_id', 'compte_source_id', 'compte_destination_id', 'montant', 'frais', 'statut', 'date_operation'];

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
