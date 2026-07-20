<?php

namespace App\Services;

use App\Models\ClientModel;
use App\Models\CompteModel;
use App\Validation\PhoneValidator;

/**
 * Service pour gérer l'authentification et la création de compte
 */
class AuthService
{
    protected $clientModel;
    protected $compteModel;
    protected $session;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->compteModel = new CompteModel();
        $this->session = session();
    }

    /**
        * Connexion directe par numéro de téléphone
     * @param string $phoneNumber
    * @return array ['success' => bool, 'message' => string, 'client_id' => int|null]
     */
    public function initiateLogin($phoneNumber)
    {
        // Valider le numéro de téléphone
        $validation = PhoneValidator::validate($phoneNumber);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message'],
                'client_id' => null,
            ];
        }

        $formattedPhone = $validation['formatted'];

        // Vérifier ou créer l'utilisateur
        $client = $this->clientModel->getByTelephone($formattedPhone);

        if (!$client) {
            $client = $this->createClient($formattedPhone);
            if (!$client) {
                return [
                    'success' => false,
                    'message' => 'Erreur lors de la création du client',
                    'client_id' => null,
                ];
            }
        }

        // Connexion directe: création de session immédiate
        $this->session->set([
            'client_id'    => $client['id'],
            'telephone'    => $client['telephone'],
            'nom'          => $client['nom'] ?? '',
            'is_logged_in' => true,
        ]);

        return [
            'success' => true,
            'message' => 'Connexion réussie pour ' . $formattedPhone,
            'client_id' => $client['id'],
        ];
    }

    /**
     * Créer un nouveau client
     * @param string $phoneNumber
     * @return array|false
     */
    public function createClient($phoneNumber)
    {
        $data = [
            'telephone' => $phoneNumber,
            'nom'       => null,
            'statut'    => 'ACTIF',
        ];

        if ($this->clientModel->insert($data)) {
            $clientId = $this->clientModel->insertID();

            $this->createCompte($clientId);

            return $this->clientModel->find($clientId);
        }

        return false;
    }

    /**
     * Créer un compte pour un client
     * @param int $clientId
     * @return array|false
     */
    public function createCompte($clientId)
    {
        $client = $this->clientModel->find($clientId);
        if (!$client) {
            return false;
        }

        $data = [
            'client_id' => $clientId,
            'solde'     => 0,
            'statut'    => 'ACTIF',
        ];

        if ($this->compteModel->insert($data)) {
            return $this->compteModel->find($this->compteModel->insertID());
        }

        return false;
    }

    /**
     * Déconnecter l'utilisateur
     */
    public function logout()
    {
        $this->session->destroy();
    }

    /**
     * Vérifier si un utilisateur est connecté
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->session->get('is_logged_in') === true;
    }

    /**
     * Obtenir l'ID de l'utilisateur connecté
     * @return int|null
     */
    public function getCurrentUserId()
    {
        return $this->session->get('client_id');
    }

    /**
     * Obtenir les données de l'utilisateur connecté
     * @return array|null
     */
    public function getCurrentUser()
    {
        $clientId = $this->getCurrentUserId();
        if ($clientId) {
            return $this->clientModel->find($clientId);
        }
        return null;
    }

    /**
     * Obtenir le compte de l'utilisateur connecté
     * @return array|null
     */
    public function getCurrentAccount()
    {
        $clientId = $this->getCurrentUserId();
        if ($clientId) {
            return $this->compteModel->getByClientId($clientId);
        }
        return null;
    }
}
