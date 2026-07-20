<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\ClientModel;
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
    protected $clientModel;
    protected $operationModel;
    protected $typeOperationModel;
    protected $baremeFraisModel;
    protected $mouvementModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->compteModel = new CompteModel();
        $this->clientModel = new ClientModel();
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

        $compte = $this->compteModel->find(session()->get('compte_id'));

        return view('client/retrait', [
            'title' => 'Retrait',
            'compte' => $compte,
            'solde' => (int) ($compte['solde'] ?? 0),
        ]);
    }

    public function doRetrait()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        $montantSaisi = $this->request->getPost('montant');

        if (filter_var($montantSaisi, FILTER_VALIDATE_INT) === false || (int) $montantSaisi <= 0) {
            return redirect()->back()->withInput()->with('error', 'Le montant du retrait doit être un entier supérieur à 0');
        }

        $montant = (int) $montantSaisi;

        $compteId = (int) session()->get('compte_id');
        $compte = $this->compteModel->find($compteId);

        if (!$compte) {
            return redirect()->to('/client/dashboard')->with('error', 'Compte introuvable');
        }

        if (($compte['statut'] ?? null) !== 'ACTIF') {
            return redirect()->back()->withInput()->with('error', 'Ce compte ne permet pas les retraits');
        }

        $typeRetrait = $this->typeOperationModel->getByCode('RETRAIT');
        if (!$typeRetrait) {
            $this->typeOperationModel->insert([
                'code' => 'RETRAIT',
                'libelle' => 'Retrait',
                'avec_frais' => 1,
                'actif' => 1,
            ]);
            $typeRetrait = $this->typeOperationModel->getByCode('RETRAIT');
        }

        $frais = $this->baremeFraisModel->getFraisForAmount($typeRetrait['id'], $montant);
        $montantTotal = $montant + $frais;
        $soldeAvant = (int) $compte['solde'];

        if ($soldeAvant < $montantTotal) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour effectuer ce retrait');
        }

        $db = db_connect();
        $db->transStart();

        $reference = OperationModel::generateReference();
        $operationId = $this->operationModel->insert([
            'reference' => $reference,
            'type_operation_id' => $typeRetrait['id'],
            'compte_source_id' => $compteId,
            'compte_destination_id' => null,
            'montant' => $montant,
            'frais' => $frais,
            'statut' => 'VALIDEE',
        ]);

        if ($operationId === false) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Impossible d’enregistrer l’opération');
        }

        $soldeApres = $soldeAvant - $montantTotal;
        $this->compteModel->update($compteId, ['solde' => $soldeApres]);

        $this->mouvementModel->insert([
            'operation_id' => $this->operationModel->insertID(),
            'compte_id' => $compteId,
            'sens' => MouvementCompteModel::DEBIT,
            'montant' => $montantTotal,
            'solde_avant' => $soldeAvant,
            'solde_apres' => $soldeApres,
        ]);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Échec de l’enregistrement du retrait');
        }

        return redirect()->to('/client/dashboard')->with('success', 'Retrait enregistré avec succès');
    }

    public function transfert()
    {
        $compte = $this->compteModel->find((int) session()->get('compte_id'));

        return view('client/transfert', [
            'title' => 'Transfert',
            'solde' => (int) ($compte['solde'] ?? 0),
        ]);
    }

    public function doTransfert()
    {
        $telephone = preg_replace('/\D/', '', (string) $this->request->getPost('telephone'));
        $montantSaisi = $this->request->getPost('montant');

        if (!preg_match('/^\d{10}$/', $telephone)) {
            return redirect()->back()->withInput()->with('error', 'Le numéro du destinataire doit contenir exactement 10 chiffres');
        }

        if (filter_var($montantSaisi, FILTER_VALIDATE_INT) === false || (int) $montantSaisi <= 0) {
            return redirect()->back()->withInput()->with('error', 'Le montant du transfert doit être un entier supérieur à 0');
        }

        if ($telephone === session()->get('telephone')) {
            return redirect()->back()->withInput()->with('error', 'Vous ne pouvez pas effectuer un transfert vers votre propre compte');
        }

        $destinataire = $this->clientModel->getByTelephone($telephone);
        if (!$destinataire || ($destinataire['statut'] ?? null) !== 'ACTIF') {
            return redirect()->back()->withInput()->with('error', 'Destinataire introuvable ou bloqué');
        }

        $compteSourceId = (int) session()->get('compte_id');
        $compteDestination = $this->compteModel->getByClientId((int) $destinataire['id']);
        if (!$compteDestination || ($compteDestination['statut'] ?? null) !== 'ACTIF') {
            return redirect()->back()->withInput()->with('error', 'Le compte du destinataire est indisponible');
        }

        $montant = (int) $montantSaisi;
        $typeTransfert = $this->typeOperationModel->getByCode('TRANSFERT');
        if (!$typeTransfert) {
            return redirect()->back()->withInput()->with('error', 'Le type d’opération TRANSFERT est indisponible');
        }

        $frais = $this->baremeFraisModel->getFraisForAmount((int) $typeTransfert['id'], $montant);
        $db = db_connect();
        $db->transBegin();

        try {
            $compteSource = $this->compteModel->find($compteSourceId);
            $compteDestination = $this->compteModel->find((int) $compteDestination['id']);

            if (!$compteSource || ($compteSource['statut'] ?? null) !== 'ACTIF') {
                throw new \RuntimeException('Le compte source est indisponible');
            }

            $soldeSourceAvant = (int) $compteSource['solde'];
            $soldeDestinationAvant = (int) $compteDestination['solde'];
            $totalDebite = $montant + $frais;

            if ($soldeSourceAvant < $totalDebite) {
                throw new \RuntimeException('Solde insuffisant pour effectuer ce transfert');
            }

            $operationId = $this->operationModel->insert([
                'reference' => OperationModel::generateReference(),
                'type_operation_id' => $typeTransfert['id'],
                'compte_source_id' => $compteSourceId,
                'compte_destination_id' => $compteDestination['id'],
                'montant' => $montant,
                'frais' => $frais,
                'statut' => 'VALIDEE',
            ]);

            if ($operationId === false
                || !$this->compteModel->update($compteSourceId, ['solde' => $soldeSourceAvant - $totalDebite])
                || !$this->compteModel->update((int) $compteDestination['id'], ['solde' => $soldeDestinationAvant + $montant])
                || !$this->mouvementModel->insert([
                    'operation_id' => $operationId,
                    'compte_id' => $compteSourceId,
                    'sens' => MouvementCompteModel::DEBIT,
                    'montant' => $totalDebite,
                    'solde_avant' => $soldeSourceAvant,
                    'solde_apres' => $soldeSourceAvant - $totalDebite,
                ])
                || !$this->mouvementModel->insert([
                    'operation_id' => $operationId,
                    'compte_id' => $compteDestination['id'],
                    'sens' => MouvementCompteModel::CREDIT,
                    'montant' => $montant,
                    'solde_avant' => $soldeDestinationAvant,
                    'solde_apres' => $soldeDestinationAvant + $montant,
                ])) {
                throw new \RuntimeException('Impossible d’enregistrer le transfert');
            }

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Échec de la transaction');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->to('/client/dashboard')->with('success', 'Transfert effectué avec succès');
    }
}
