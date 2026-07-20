<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\CompteModel;
use App\Models\MouvementCompteModel;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class OperationController extends BaseController
{
    protected $compteModel;
    protected $operationModel;
    protected $typeOperationModel;
    protected $baremeFraisModel;
    protected $mouvementModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->compteModel = new CompteModel();
        $this->operationModel = new OperationModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->baremeFraisModel = new BaremeFraisModel();
        $this->mouvementModel = new MouvementCompteModel();
    }

    public function depot()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $compte = $this->compteModel->find(session()->get('compte_id'));

        return view('client/depot', [
            'title' => 'Dépôt',
            'compte' => $compte,
            'solde' => (int) ($compte['solde'] ?? 0),
        ]);
    }

    public function doDepot()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $montant = (int) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Le montant du dépôt doit être supérieur à 0');
        }

        $compteId = (int) session()->get('compte_id');
        $compte = $this->compteModel->find($compteId);

        if (!$compte) {
            return redirect()->to('/client/dashboard')->with('error', 'Compte introuvable');
        }

        $typeDepot = $this->typeOperationModel->getByCode('DEPOT');
        if (!$typeDepot) {
            $this->typeOperationModel->insert([
                'code' => 'DEPOT',
                'libelle' => 'Dépôt',
                'avec_frais' => 0,
                'actif' => 1,
            ]);
            $typeDepot = $this->typeOperationModel->getByCode('DEPOT');
        }

        $frais = 0;
        $db = db_connect();
        $db->transStart();

        $reference = OperationModel::generateReference();
        $operationId = $this->operationModel->insert([
            'reference' => $reference,
            'type_operation_id' => $typeDepot['id'],
            'compte_source_id' => null,
            'compte_destination_id' => $compteId,
            'montant' => $montant,
            'frais' => $frais,
            'statut' => 'VALIDEE',
        ]);

        if (!$operationId) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Impossible d’enregistrer l’opération');
        }

        $soldeAvant = (int) $compte['solde'];
        $soldeApres = $soldeAvant + $montant;

        $this->compteModel->update($compteId, ['solde' => $soldeApres]);

        $this->mouvementModel->insert([
            'operation_id' => $this->operationModel->insertID(),
            'compte_id' => $compteId,
            'sens' => MouvementCompteModel::CREDIT,
            'montant' => $montant,
            'solde_avant' => $soldeAvant,
            'solde_apres' => $soldeApres,
        ]);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Échec de l’enregistrement du dépôt');
        }

        return redirect()->to('/client/dashboard')->with('success', 'Dépôt enregistré avec succès');
    }

    public function retrait()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        return view('client/retrait', ['title' => 'Retrait']);
    }

    public function doRetrait()
    {
        return redirect()->to('/client/dashboard')->with('info', 'Fonctionnalité de retrait à venir');
    }

    public function transfert()
    {
        return redirect()->to('/client/dashboard')->with('info', 'Fonctionnalité de transfert à venir');
    }
}
