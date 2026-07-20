<?php

namespace App\Services;

use App\Models\BaremeFraisModel;
use App\Models\TypeOperationModel;
use InvalidArgumentException;
use RuntimeException;

class FraisService
{
    public function __construct(
        private ?BaremeFraisModel $baremeModel = null,
        private ?TypeOperationModel $typeOperationModel = null,
    ) {
        $this->baremeModel ??= new BaremeFraisModel();
        $this->typeOperationModel ??= new TypeOperationModel();
    }

    /**
     * @param array{est_interne: bool, commission_externe: int} $operateur
     * @return array{
     *     montant: int,
     *     frais_transfert: int,
     *     commission_externe: int,
     *     frais_retrait: int,
     *     frais_total: int,
     *     total_debite: int
     * }
     */
    public function calculerTransfert(int $montant, array $operateur, bool $inclureFraisRetrait = false): array
    {
        if ($montant <= 0) {
            throw new InvalidArgumentException('Le montant doit être supérieur à zéro');
        }

        $fraisTransfert = $this->trouverFrais('TRANSFERT', $montant);
        $commissionExterne = !empty($operateur['est_interne'])
            ? 0
            : max(0, (int) ($operateur['commission_externe'] ?? 0));
        $fraisRetrait = $inclureFraisRetrait
            ? $this->trouverFrais('RETRAIT', $montant)
            : 0;

        $fraisTotal = $fraisTransfert + $commissionExterne + $fraisRetrait;

        return [
            'montant' => $montant,
            'frais_transfert' => $fraisTransfert,
            'commission_externe' => $commissionExterne,
            'frais_retrait' => $fraisRetrait,
            'frais_total' => $fraisTotal,
            'total_debite' => $montant + $fraisTotal,
        ];
    }

    private function trouverFrais(string $code, int $montant): int
    {
        $type = $this->typeOperationModel->getByCode($code);
        if (!$type) {
            throw new RuntimeException("Le type d’opération {$code} est indisponible");
        }

        $frais = $this->baremeModel->findFraisForAmount((int) $type['id'], $montant);
        if ($frais === null) {
            throw new RuntimeException("Aucune tranche active {$code} ne couvre le montant {$montant} Ar");
        }

        return $frais;
    }
}
