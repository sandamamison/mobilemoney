<?php

namespace App\Controllers;

use App\Services\AuthService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Auth extends BaseController
{
    protected $authService;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->authService = new AuthService();
    }

    /**
     * Page de connexion
     */
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $phone = $this->request->getPost('phone_number');

            $result = $this->authService->initiateLogin($phone);

            if ($result['success']) {
                return redirect()->to('/dashboard')->with('success', $result['message']);
            }

            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        // Si déjà connecté, rediriger vers le tableau de bord
        if ($this->authService->isLoggedIn()) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        $this->authService->logout();
        return redirect()->to('/auth/login')->with('success', 'Vous avez été déconnecté');
    }
}
