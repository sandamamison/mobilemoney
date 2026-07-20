<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientModel;
use App\Models\ComptesModel;
use App\Models\OperationModel;
use App\Models\BaremeFraisModel;


class OperateurController extends BaseController
{
    public function index()
    {
        $clientModel = new ClientModel();
        $comptesModel = new ComptesModel();
        $operationModel = new OperationModel();
        $autreOperateurModel = new \App\Models\AutreOperateurModel();

        $nombreDeClients = $clientModel->countAll();
        $nombreDeComptes = $comptesModel->countAll();
        $totalDesSoldes = $comptesModel->sommesoldes();
        $nombreDOperations = $operationModel->countAll();
        
        $nombreOperateursExternes = $autreOperateurModel->countAll();
        
        $gainsRetraits = $operationModel->gainsRetraits();
        $gainsTransfertsInternes = $operationModel->gainsTransfertsInternes();
        $totalGainsInternes = ($gainsRetraits['gain_total'] ?? 0) + ($gainsTransfertsInternes['gain_total'] ?? 0);
        
        $gainNetExternes = $operationModel->gainNetTransfertsExternes();
        
        $reglements = $operationModel->montantsAEnvoyerParOperateur();
        $totalARegler = (int) array_sum(array_column($reglements, 'total_a_regler'));

        $totalGainNet = $totalGainsInternes + $gainNetExternes;

        $data = [
            'nombreDeClients' => $nombreDeClients,
            'nombreDeComptes' => $nombreDeComptes,
            'totalDesSoldes' => $totalDesSoldes,
            'nombreDOperations' => $nombreDOperations,
            'nombreOperateursExternes' => $nombreOperateursExternes,
            'totalGainsInternes' => $totalGainsInternes,
            'gainNetExternes' => $gainNetExternes,
            'totalARegler' => $totalARegler,
            'totalGainNet' => $totalGainNet,
        ];

        return view('operateur/dashboard', $data);
    }
}
