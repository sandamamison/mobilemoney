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
        $baremeFraisModel = new BaremeFraisModel();

        $nombreDeClients = $clientModel->countAll();
        $nombreDeComptes = $comptesModel->countAll();
        $totalDesSoldes = $comptesModel->sommesoldes();
        $nombreDOperations = $operationModel->countAll();
        $totalDesFrais = $operationModel->totalgain();

        $data = [
            'nombreDeClients' => $nombreDeClients,
            'nombreDeComptes' => $nombreDeComptes,
            'totalDesSoldes' => $totalDesSoldes,
            'nombreDOperations' => $nombreDOperations,
            'totalDesFrais' => $totalDesFrais,
        ];

        return view('operateur/dashboard', $data);
    }
}
