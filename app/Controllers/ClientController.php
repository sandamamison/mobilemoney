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

        $operations = $this->regrouperOperations(
            $this->operationModel->getHistoriqueCompte((int) $compteId, 30),
            (int) $compteId,
            5,
        );

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
        $operations = $this->regrouperOperations(
            $this->operationModel->getHistoriqueCompte((int) $compteId, 100),
            (int) $compteId,
            20,
        );

        return view('client/operations', [
            'title' => 'Historique',
            'operations' => $operations,
        ]);
    }

    /**
     * Regroupe uniquement les transferts multiples émis par le compte courant.
     * Une réception reste une opération individuelle dans l'historique du bénéficiaire.
     */
    private function regrouperOperations(array $operations, int $compteId, int $limite): array
    {
        $groupes = [];

        foreach ($operations as $operation) {
            $estSortante = (int) ($operation['compte_source_id'] ?? 0) === $compteId;
            $referenceGroupe = trim((string) ($operation['groupe_reference'] ?? ''));
            $cle = $estSortante && $referenceGroupe !== ''
                ? 'groupe:' . $referenceGroupe
                : 'operation:' . ($operation['id'] ?? $operation['reference']);

            if (!isset($groupes[$cle])) {
                $groupes[$cle] = [
                    'est_groupe' => $estSortante && $referenceGroupe !== '',
                    'groupe_reference' => $referenceGroupe ?: null,
                    'reference' => $operation['reference'] ?? '',
                    'libelle' => $operation['libelle'] ?? 'Opération',
                    'date_operation' => $operation['date_operation'] ?? '',
                    'est_sortante' => $estSortante,
                    'montant_total' => 0,
                    'frais_total' => 0,
                    'nombre_operations' => 0,
                    'operations' => [],
                ];
            }

            $groupes[$cle]['montant_total'] += (int) ($operation['montant'] ?? 0);
            $groupes[$cle]['frais_total'] += (int) ($operation['frais'] ?? 0);
            $groupes[$cle]['nombre_operations']++;
            $groupes[$cle]['operations'][] = $operation;
        }

        return array_slice(array_values($groupes), 0, $limite);
    }



    public function pourcentage(){
        return view('client/pourcentage');
    }
}
