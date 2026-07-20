<?php

namespace App\Models;

use CodeIgniter\Model;

class TypesOperationModel extends Model
{
    protected $table            = 'typesoperations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['code', 'libelle', 'avec_frais', 'actif'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

}
