<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OperationModel;

class ReglementOperateurController extends BaseController
{
    /**
     * Affiche les montants à envoyer à chaque opérateur externe.
     */
    public function index()
    {
        $operationModel = new OperationModel();
        $reglements = $operationModel->montantsAEnvoyerParOperateur();

        $data = [
            'reglements'       => $reglements,
            'totalMontant'     => (int) array_sum(array_column($reglements, 'montant_total')),
            'totalCommission'  => (int) array_sum(array_column($reglements, 'commission_due')),
            'totalARegler'     => (int) array_sum(array_column($reglements, 'total_a_regler')),
            'totalOperations'  => (int) array_sum(array_column($reglements, 'nombre_operations')),
        ];

        return view('reglements/index', $data);
    }
}
