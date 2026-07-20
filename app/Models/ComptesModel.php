<?php

namespace App\Models;

use CodeIgniter\Model;

class ComptesModel extends Model
{
    protected $table            = 'comptes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['client_id', 'solde', 'statut', 'date_creation'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    public function sommesoldes(){
        $sql = 'SELECT SUM(solde) AS total FROM comptes';
        $query = $this->db->query($sql);
        return $query->getRow()->total ?? 0;
    }

}
