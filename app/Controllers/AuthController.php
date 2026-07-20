<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\CompteModel;
use App\Models\PrefixeOperateurModel;
use App\Validation\PhoneValidator;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AuthController extends BaseController
{
    protected $clientModel;
    protected $compteModel;
    protected $prefixeModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->clientModel = new ClientModel();
        $this->compteModel = new CompteModel();
        $this->prefixeModel = new PrefixeOperateurModel();
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function index()
    {
        // Si déjà connecté, rediriger
        if (session()->get('is_logged_in')) {
            return redirect()->to('/client/dashboard');
        }

        return view('auth/login', [
            'validation' => service('validation'),
        ]);
    }

    /**
     * Traiter la connexion
     */
    public function connexion()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/login');
        }

        $telephone = $this->request->getPost('telephone');
        $password = $this->request->getPost('password');

        // Nettoyer le numéro
        $telephoneClean = preg_replace('/[^0-9+]/', '', $telephone);

        // Valider le format
        if (!preg_match('/^\d{10}$/', $telephoneClean) && !preg_match('/^\d{8}$/', $telephoneClean)) {
            return redirect()->back()->withInput()->with('error', 'Le numéro doit contenir 8 ou 10 chiffres');
        }

        // Valider le password
        if (empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Le mot de passe est requis');
        }

        // Si 8 chiffres, assumer préfixe 033
        if (strlen($telephoneClean) === 8) {
            $telephoneClean = '033' . $telephoneClean;
        }

        // Extraire le préfixe
        $prefixe = substr($telephoneClean, 0, 3);

        // Vérifier le préfixe
        if (!$this->prefixeModel->isPrefixActif($prefixe)) {
            return redirect()->back()->withInput()->with('error', 'Préfixe invalide ou non supporté');
        }

        // Chercher ou créer le client
        $client = $this->clientModel->getByTelephone($telephoneClean);

        if (!$client) {
            // Créer un nouveau client avec le password hashé
            if (!$this->clientModel->insert([
                'telephone' => $telephoneClean, 
                'password'  => password_hash($password, PASSWORD_DEFAULT),
                'statut'    => 'ACTIF'
            ])) {
                return redirect()->back()->with('error', 'Erreur lors de la création du client');
            }
            $clientId = $this->clientModel->insertID();
            $client = $this->clientModel->find($clientId);
        } else {
            // Vérifier le mot de passe
            if (!password_verify($password, $client['password'])) {
                return redirect()->back()->withInput()->with('error', 'Mot de passe incorrect');
            }
        }

        // Vérifier que le client n'est pas bloqué
        if ($client['statut'] === 'BLOQUE') {
            return redirect()->back()->with('error', 'Ce compte a été bloqué');
        }

        // Chercher ou créer le compte
        $compte = $this->compteModel->getByClientId($client['id']);

        if (!$compte) {
            if (!$this->compteModel->insert(['client_id' => $client['id'], 'solde' => 0, 'statut' => 'ACTIF'])) {
                return redirect()->back()->with('error', 'Erreur lors de la création du compte');
            }
            $compteId = $this->compteModel->insertID();
            $compte = $this->compteModel->find($compteId);
        }

        // Vérifier que le compte n'est pas bloqué
        if ($compte['statut'] === 'BLOQUE') {
            return redirect()->back()->with('error', 'Ce compte a été bloqué');
        }

        // Créer la session
        session()->set([
            'client_id'    => $client['id'],
            'compte_id'    => $compte['id'],
            'telephone'    => $client['telephone'],
            'nom'          => $client['nom'] ?? '',
            'is_logged_in' => true,
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Connexion réussie');
    }

    /**
     * Déconnexion
     */
    public function deconnexion()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Déconnexion réussie');
    }
}
