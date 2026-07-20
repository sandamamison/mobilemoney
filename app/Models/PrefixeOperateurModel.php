<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeOperateurModel extends Model
{
    protected $table            = 'prefixes_operateur';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'prefixe',
        'actif',
    ];
    protected $useTimestamps = false;

    public function isPrefixActif(string $prefixe): bool
    {
        return $this->where('prefixe', $prefixe)->where('actif', 1)->first() !== null;
    }
}
