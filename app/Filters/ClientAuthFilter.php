<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ClientAuthFilter implements FilterInterface
{
    /**
     * Avant l'exécution
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Vous devez d\'abord vous connecter');
        }

        // Vérifier que client_id et compte_id existent dans la session
        if (!session()->get('client_id') || !session()->get('compte_id')) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Session invalide. Veuillez vous reconnecter');
        }
    }

    /**
     * Après l'exécution
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après
    }
}
