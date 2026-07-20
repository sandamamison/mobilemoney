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

    public function totalgain(){
        $sql = 'SELECT SUM(frais) AS total FROM operations';
        $query = $this->db->query($sql);
        return $query->getRow()->total ?? 0;
    }

}
