<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PourcentageEpargne;
class PourcentageController extends BaseController
{
    public function store(){
        $id = $this->request->getPost('id');
        $pourcentage = $this->request->getPost('pourcentage');
        $model = new PourcentageEpargne();

        $data = [
            'id_client' => $id,
            'pourcentage' => $pourcentage
        ];
        $model->insert($data);
        return redirect()->to('/client/dashboard');
    }
}
