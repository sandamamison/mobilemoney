<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\CompteModel;
use App\Models\OperationModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class ClientController extends BaseController
{
    protected $clientModel;
    protected $compteModel;
    protected $operationModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->clientModel = new ClientModel();
        $this->compteModel = new CompteModel();
        $this->operationModel = new OperationModel();
    }

    /**
     * Tableau de bord du client
     */
    public function dashboard()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Vous devez vous connecter pour accéder à votre espace');
        }

        $clientId = session()->get('client_id');
        $compteId = session()->get('compte_id');

        $client = $this->clientModel->find($clientId);
        $compte = $this->compteModel->find($compteId);

        if (!$client || !$compte) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Session invalide. Veuillez vous reconnecter');
        }

        $operations = $this->operationModel->getHistoriqueCompte((int) $compteId, 5);

        return view('client/dashboard', [
            'title' => 'Tableau de bord',
            'client' => $client,
            'compte' => $compte,
            'solde' => (int) ($compte['solde'] ?? 0),
            'operations' => $operations,
        ]);
    }

    /**
     * Historique des opérations du client
     */
    public function operations()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $compteId = session()->get('compte_id');
        $operations = $this->operationModel->getHistoriqueCompte((int) $compteId, 15);

        return view('client/operations', [
            'title' => 'Historique',
            'operations' => $operations,
        ]);
    }
}
