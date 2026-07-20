<?php

namespace App\Controllers;

class OperationController extends BaseController
{
    public function depot()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }

        return view('client/depot', ['title' => 'Dépôt']);
    }

    public function doDepot()
    {
        return redirect()->to('/client/dashboard')->with('info', 'Fonctionnalité de dépôt à venir');
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
