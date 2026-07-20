<?php

namespace App\Services;

use App\Models\PrefixeModel;
use InvalidArgumentException;

class DetectionOperateurService
{
    public function __construct(private ?PrefixeModel $prefixeModel = null)
    {
        $this->prefixeModel ??= new PrefixeModel();
    }

    /**
     * Nettoie un numéro et retourne les informations de son opérateur.
     *
     * @return array{
     *     telephone: string,
     *     prefixe: string,
     *     nom: string,
     *     est_interne: bool,
     *     commission_externe: int
     * }
     */
    public function detecter(string $numero): array
    {
        $telephone = preg_replace('/\D/', '', $numero);

        if (!preg_match('/^\d{10}$/', $telephone)) {
            throw new InvalidArgumentException('Le numéro doit contenir exactement 10 chiffres');
        }

        $prefixe = substr($telephone, 0, 3);
        $operateur = $this->prefixeModel->getByPrefixe($prefixe);

        if (!$operateur) {
            throw new InvalidArgumentException('Préfixe inconnu');
        }

        if ((int) $operateur['actif'] !== 1) {
            throw new InvalidArgumentException('Cet opérateur est temporairement désactivé');
        }

        return [
            'telephone' => $telephone,
            'prefixe' => $prefixe,
            'nom' => $operateur['nom_operateur'] ?: 'Opérateur ' . $prefixe,
            'est_interne' => (bool) $operateur['est_interne'],
            'commission_externe' => (int) $operateur['commission_externe'],
        ];
    }
}
