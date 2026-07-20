<?php

namespace App\Services;

use App\Models\ClientModel;
use App\Models\CompteModel;
use App\Models\MouvementCompteModel;
use App\Models\OperationModel;
use App\Models\TypeOperationModel;
use CodeIgniter\Database\BaseConnection;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class OperationService
{
    public function __construct(
        private ?DetectionOperateurService $detectionService = null,
        private ?FraisService $fraisService = null,
        private ?ClientModel $clientModel = null,
        private ?CompteModel $compteModel = null,
        private ?OperationModel $operationModel = null,
        private ?MouvementCompteModel $mouvementModel = null,
        private ?TypeOperationModel $typeOperationModel = null,
        private ?BaseConnection $db = null,
    ) {
        $this->detectionService ??= new DetectionOperateurService();
        $this->fraisService ??= new FraisService();
        $this->clientModel ??= new ClientModel();
        $this->compteModel ??= new CompteModel();
        $this->operationModel ??= new OperationModel();
        $this->mouvementModel ??= new MouvementCompteModel();
        $this->typeOperationModel ??= new TypeOperationModel();
        $this->db ??= db_connect();
    }

    public function preparerTransfert(string $telephone, int $montant, bool $inclureFraisRetrait = false): array
    {
        $operateur = $this->detectionService->detecter($telephone);
        $calcul = $this->fraisService->calculerTransfert($montant, $operateur, $inclureFraisRetrait);

        return [
            'telephone' => $operateur['telephone'],
            'operateur' => $operateur,
            'calcul' => $calcul,
            'inclure_frais_retrait' => $inclureFraisRetrait,
        ];
    }

    /**
     * Exécute une préparation retournée par preparerTransfert().
     */
    public function executerTransfert(int $compteSourceId, array $preparation, ?string $groupeReference = null): array
    {
        $operateur = $preparation['operateur'] ?? null;
        $calcul = $preparation['calcul'] ?? null;
        if (!$operateur || !$calcul) {
            throw new InvalidArgumentException('Préparation de transfert invalide');
        }

        $typeTransfert = $this->typeOperationModel->getByCode('TRANSFERT');
        if (!$typeTransfert) {
            throw new RuntimeException('Le type d’opération TRANSFERT est indisponible');
        }

        $this->db->transBegin();
        try {
            $source = $this->compteModel->find($compteSourceId);
            if (!$source || ($source['statut'] ?? null) !== 'ACTIF') {
                throw new RuntimeException('Le compte source est introuvable ou bloqué');
            }

            $destination = $this->trouverDestinationInterne($operateur, $compteSourceId);
            $soldeSourceAvant = (int) $source['solde'];
            $totalDebite = (int) $calcul['total_debite'];
            if ($soldeSourceAvant < $totalDebite) {
                throw new RuntimeException('Solde insuffisant pour effectuer ce transfert');
            }

            $reference = OperationModel::generateReference();
            $operationId = $this->operationModel->insert([
                'reference' => $reference,
                'type_operation_id' => $typeTransfert['id'],
                'compte_source_id' => $compteSourceId,
                'compte_destination_id' => $destination['id'] ?? null,
                'montant' => $calcul['montant'],
                'frais' => $calcul['frais_total'],
                'statut' => 'VALIDEE',
                'destinataire_telephone' => $operateur['telephone'],
                'operateur_destination' => $operateur['nom'],
                'transfert_externe' => $operateur['est_interne'] ? 0 : 1,
                'frais_transfert' => $calcul['frais_transfert'],
                'commission_externe' => $calcul['commission_externe'],
                'frais_retrait_inclus' => $calcul['frais_retrait'],
                'groupe_reference' => $groupeReference,
            ]);
            if (!$operationId) {
                throw new RuntimeException('Impossible d’enregistrer le transfert');
            }

            $soldeSourceApres = $soldeSourceAvant - $totalDebite;
            if (!$this->compteModel->update($compteSourceId, ['solde' => $soldeSourceApres])) {
                throw new RuntimeException('Impossible de débiter le compte source');
            }
            if (!$this->mouvementModel->insert([
                'operation_id' => $operationId,
                'compte_id' => $compteSourceId,
                'sens' => MouvementCompteModel::DEBIT,
                'montant' => $totalDebite,
                'solde_avant' => $soldeSourceAvant,
                'solde_apres' => $soldeSourceApres,
            ])) {
                throw new RuntimeException('Impossible d’enregistrer le débit source');
            }

            if ($destination) {
                $soldeDestinationAvant = (int) $destination['solde'];
                $soldeDestinationApres = $soldeDestinationAvant + (int) $calcul['montant'];
                if (!$this->compteModel->update((int) $destination['id'], ['solde' => $soldeDestinationApres])) {
                    throw new RuntimeException('Impossible de créditer le destinataire');
                }
                if (!$this->mouvementModel->insert([
                    'operation_id' => $operationId,
                    'compte_id' => $destination['id'],
                    'sens' => MouvementCompteModel::CREDIT,
                    'montant' => $calcul['montant'],
                    'solde_avant' => $soldeDestinationAvant,
                    'solde_apres' => $soldeDestinationApres,
                ])) {
                    throw new RuntimeException('Impossible d’enregistrer le crédit destinataire');
                }
            }

            if (!$this->db->transStatus()) {
                throw new RuntimeException('Échec de la transaction de transfert');
            }
            $this->db->transCommit();

            return [
                'operation_id' => (int) $operationId,
                'reference' => $reference,
                'total_debite' => $totalDebite,
                'solde_apres' => $soldeSourceApres,
                'transfert_externe' => !$operateur['est_interne'],
                'groupe_reference' => $groupeReference,
            ];
        } catch (Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    private function trouverDestinationInterne(array $operateur, int $compteSourceId): ?array
    {
        if (!$operateur['est_interne']) {
            return null;
        }

        $client = $this->clientModel->getByTelephone($operateur['telephone']);
        if (!$client || ($client['statut'] ?? null) !== 'ACTIF') {
            throw new RuntimeException('Le destinataire interne est introuvable ou bloqué');
        }

        $destination = $this->compteModel->getByClientId((int) $client['id']);
        if (!$destination || ($destination['statut'] ?? null) !== 'ACTIF') {
            throw new RuntimeException('Le compte destinataire est introuvable ou bloqué');
        }
        if ((int) $destination['id'] === $compteSourceId) {
            throw new RuntimeException('Vous ne pouvez pas transférer vers votre propre compte');
        }

        return $destination;
    }
}
