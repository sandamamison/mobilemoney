<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeAutreOperateurModel extends Model
{
    protected $table            = 'prefixes_autres_operateurs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['prefixe', 'autre_operateur_id', 'actif'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Validation
    protected $validationRules = [
        'prefixe'            => 'required|min_length[3]|max_length[5]|regex_match[/^[0-9]+$/]',
        'autre_operateur_id' => 'required|is_natural_no_zero',
    ];
    protected $validationMessages = [
        'prefixe' => [
            'required'    => 'Le préfixe est obligatoire.',
            'min_length'  => 'Le préfixe doit comporter au moins 3 chiffres.',
            'max_length'  => 'Le préfixe ne peut pas dépasser 5 chiffres.',
            'regex_match' => 'Le préfixe ne doit contenir que des chiffres.',
        ],
        'autre_operateur_id' => [
            'required'           => 'Veuillez sélectionner un opérateur.',
            'is_natural_no_zero' => 'L\'opérateur sélectionné est invalide.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Retourne tous les préfixes avec le nom de l'opérateur associé.
     */
    public function getAllWithOperateur(): array
    {
        return $this->select('prefixes_autres_operateurs.*, autres_operateurs.nom AS operateur_nom')
            ->join('autres_operateurs', 'autres_operateurs.id = prefixes_autres_operateurs.autre_operateur_id', 'left')
            ->orderBy('prefixes_autres_operateurs.prefixe', 'ASC')
            ->findAll();
    }

    /**
     * Vérifie si un préfixe existe déjà (pour empêcher les doublons).
     * Exclut éventuellement l'id courant lors d'une mise à jour.
     */
    public function prefixeExiste(string $prefixe, ?int $excludeId = null): bool
    {
        $builder = $this->where('prefixe', $prefixe);
        if ($excludeId !== null) {
            $builder = $builder->where('id !=', $excludeId);
        }
        return $builder->first() !== null;
    }

    /**
     * Active ou désactive un préfixe (toggle).
     */
    public function toggleActif(int $id): bool
    {
        $prefixe = $this->find($id);
        if (!$prefixe) {
            return false;
        }
        $nouvelEtat = $prefixe['actif'] == 1 ? 0 : 1;
        return $this->update($id, ['actif' => $nouvelEtat]);
    }

    /**
     * Retourne les préfixes actifs d'un opérateur donné.
     */
    public function getActifsByOperateur(int $operateurId): array
    {
        return $this->where('autre_operateur_id', $operateurId)
            ->where('actif', 1)
            ->findAll();
    }

    /**
     * Retrouve l'opérateur associé à un préfixe de téléphone.
     * Retourne null si aucun préfixe actif ne correspond.
     */
    public function findOperateurByNumero(string $telephone): ?array
    {
        $prefixes = $this->select('prefixes_autres_operateurs.*, autres_operateurs.nom AS operateur_nom, autres_operateurs.commission')
            ->join('autres_operateurs', 'autres_operateurs.id = prefixes_autres_operateurs.autre_operateur_id', 'inner')
            ->where('prefixes_autres_operateurs.actif', 1)
            ->where('autres_operateurs.actif', 1)
            ->findAll();

        foreach ($prefixes as $p) {
            if (str_starts_with($telephone, $p['prefixe'])) {
                return $p;
            }
        }
        return null;
    }
}
