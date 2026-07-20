<?php

namespace App\Models;

use CodeIgniter\Model;

class MouvementCompteModel extends Model
{
    protected $table            = 'mouvements_comptes';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'operation_id',
        'compte_id',
        'sens',
        'montant',
        'solde_avant',
        'solde_apres',
    ];
    protected $useTimestamps = false;

    const CREDIT = 'CREDIT';
    const DEBIT = 'DEBIT';

    /**
     * Récupérer les mouvements d'un compte
     */
    public function getMouvementsCompte(int $compteId, int $limit = 20): array
    {
        return $this->where('compte_id', $compteId)
            ->orderBy('date_mouvement', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Vérifier que le sens est valide
     */
    public static function isValidSens(string $sens): bool
    {
        return in_array($sens, [self::CREDIT, self::DEBIT]);
    }
}
