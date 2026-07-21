<?php

namespace App\Models;

use CodeIgniter\Model;

class PourcentageEpargne extends Model
{
    protected $table            = 'pourcentageepargnes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_client', 'pourcentage'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

}
