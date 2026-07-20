<?php

namespace App\Models;

use CodeIgniter\Model;

class CompteModel extends Model
{
    protected $table            = 'comptes';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'client_id',
        'solde',
        'statut',
    ];
    protected $useTimestamps = false;
    protected $validationRules = [
        'client_id' => 'required|is_not_unique[clients.id]',
        'solde'     => 'permit_empty|integer',
        'statut'    => 'permit_empty|string',
    ];

    public function getByClientId(int $clientId): ?array
    {
        return $this->where('client_id', $clientId)->first();
    }

    public function getSolde(int $compteId): int
    {
        $compte = $this->find($compteId);

        return $compte ? (int) $compte['solde'] : 0;
    }
}
