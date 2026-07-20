<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'telephone',
        'nom',
        'statut',
    ];
    protected $useTimestamps = false;
    protected $validationRules = [
        'telephone' => 'required|is_unique[clients.telephone]',
        'nom'       => 'permit_empty|string',
    ];

    public function getByTelephone(string $telephone): ?array
    {
        return $this->where('telephone', $telephone)->first();
    }
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['telephone', 'nom', 'statut', 'date_creation'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];
}
