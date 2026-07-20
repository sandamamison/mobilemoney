<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'types_operations';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'code',
        'libelle',
        'avec_frais',
        'actif',
    ];
    protected $useTimestamps = false;

    public function getByCode(string $code): ?array
    {
        return $this->where('code', $code)->where('actif', 1)->first();
    }

    public function getActifs(): array
    {
        return $this->where('actif', 1)->findAll();
    }
}
