<?php

namespace App\Controllers;

use App\Models\BaremeFraisModel;
use App\Models\CompteModel;
use App\Models\MouvementCompteModel;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;
use App\Services\OperationService;
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
    protected $operationService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->compteModel = new CompteModel();
        $this->operationModel = new OperationModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->baremeFraisModel = new BaremeFraisModel();
        $this->mouvementModel = new MouvementCompteModel();
        $this->operationService = new OperationService();
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
        try {
            $telephone = preg_replace('/\D/', '', (string) $this->request->getPost('telephone'));
            $montant = filter_var($this->request->getPost('montant'), FILTER_VALIDATE_INT);
            $inclureRetrait = $this->request->getPost('inclure_frais_retrait') === '1';

            if ($montant === false || $montant <= 0) {
                throw new \InvalidArgumentException('Le montant doit être un entier supérieur à zéro');
            }
            if ($telephone === session()->get('telephone')) {
                throw new \InvalidArgumentException('Vous ne pouvez pas transférer vers votre propre compte');
            }

            // Les données sont recalculées au moment de la confirmation pour éviter toute modification côté navigateur.
            $preparation = $this->operationService->preparerTransfert($telephone, (int) $montant, $inclureRetrait);

            if ($this->request->getPost('action') === 'confirmer') {
                $resultat = $this->operationService->executerTransfert((int) session()->get('compte_id'), $preparation);

                return redirect()->to('/client/dashboard')->with(
                    'success',
                    'Transfert effectué avec succès. Référence : ' . $resultat['reference'],
                );
            }

            $compte = $this->compteModel->find((int) session()->get('compte_id'));

            return view('client/transfert', [
                'title' => 'Confirmer le transfert',
                'solde' => (int) ($compte['solde'] ?? 0),
                'resume' => $preparation,
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function transfertMultiple()
    {
        $compte = $this->compteModel->find((int) session()->get('compte_id'));

        return view('client/transfert_multiple', [
            'title' => 'Envoi multiple',
            'solde' => (int) ($compte['solde'] ?? 0),
        ]);
    }

    public function doTransfertMultiple()
    {
        try {
            $numerosSaisis = $this->request->getPost('numeros');
            if (is_array($numerosSaisis)) {
                $numeros = array_values(array_filter(array_map(
                    static fn ($numero) => trim((string) $numero),
                    $numerosSaisis,
                )));
            } else {
                // Compatibilité avec l'ancien formulaire à zone de texte.
                $numeros = array_values(array_filter(array_map(
                    'trim',
                    preg_split('/[\r\n,;]+/', (string) $numerosSaisis) ?: [],
                )));
            }
            $montantTotal = filter_var($this->request->getPost('montant_total'), FILTER_VALIDATE_INT);
            $inclureRetrait = $this->request->getPost('inclure_frais_retrait') === '1';
            if ($montantTotal === false || $montantTotal <= 0) {
                throw new \InvalidArgumentException('Le montant total doit être un entier supérieur à zéro');
            }

            // Recalcul complet aussi bien pour l’aperçu que pour la confirmation.
            $preparation = $this->operationService->preparerTransfertsMultiples($numeros, (int) $montantTotal, $inclureRetrait);

            if ($this->request->getPost('action') === 'confirmer') {
                $resultat = $this->operationService->executerTransfertsMultiples((int) session()->get('compte_id'), $preparation);

                return redirect()->to('/client/dashboard')->with(
                    'success',
                    $resultat['nombre_operations'] . ' transferts effectués. Groupe : ' . $resultat['groupe_reference'],
                );
            }

            $compte = $this->compteModel->find((int) session()->get('compte_id'));
            return view('client/transfert_multiple', [
                'title' => 'Confirmer l’envoi multiple',
                'solde' => (int) ($compte['solde'] ?? 0),
                'resume' => $preparation,
                'numeros_saisis' => $numeros,
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
