<?php

namespace App\Models;

use CodeIgniter\Model;

class AutreOperateurModel extends Model
{
    protected $table            = 'autres_operateurs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nom', 'commission', 'actif'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Validation
    protected $validationRules = [
        'nom'        => 'required|min_length[2]|max_length[100]',
        'commission' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
    ];
    protected $validationMessages   = [
        'nom' => [
            'required'   => 'Le nom de l\'opérateur est obligatoire.',
            'min_length' => 'Le nom doit comporter au moins 2 caractères.',
            'max_length' => 'Le nom ne peut pas dépasser 100 caractères.',
        ],
        'commission' => [
            'required'                  => 'Le pourcentage de commission est obligatoire.',
            'numeric'                   => 'La commission doit être un nombre.',
            'greater_than_equal_to'     => 'La commission ne peut pas être négative.',
            'less_than_equal_to'        => 'La commission ne peut pas dépasser 100 %.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Retourne tous les opérateurs actifs.
     */
    public function getActifs(): array
    {
        return $this->where('actif', 1)->orderBy('nom', 'ASC')->findAll();
    }

    /**
     * Active ou désactive un opérateur (toggle).
     */
    public function toggleActif(int $id): bool
    {
        $operateur = $this->find($id);
        if (!$operateur) {
            return false;
        }
        $nouvelEtat = $operateur['actif'] == 1 ? 0 : 1;
        return $this->update($id, ['actif' => $nouvelEtat]);
    }
}
