<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\BaremeFraisModel;
use App\Models\TypesOperationModel;

class BaremeController extends BaseController
{
    public function index()
    {
        $model = new BaremeFraisModel();
        $typesOperationModel = new TypesOperationModel();
        
        $baremes = $model->findAll();
        $typesOperations = $typesOperationModel->findAll();
        
        $data = [
            'baremes' => $baremes,
            'typesOperations' => $typesOperations,
        ];
        
        return view('bareme/dashboard', $data);
        
    }

    public function create(){
        $typesOperationModel = new TypesOperationModel();
        $data = [
            'typesOperations' => $typesOperationModel->findAll(),
        ];
        return view('bareme/form', $data);
    }

    public function store(){
        $model = new BaremeFraisModel();
        $data = [
            'type_operation_id' => $this->request->getPost('type_operation_id'),
            'montant_min'       => $this->request->getPost('montant_min'),
            'montant_max'       => $this->request->getPost('montant_max'),
            'frais'             => $this->request->getPost('frais'),
            'actif'             => $this->request->getPost('actif'),
        ];
        $model->insert($data);
        return redirect()->to('/bareme');
    }

    public function delete(){
        $model = new BaremeFraisModel();
        $id = $this->request->getPost('id');
        if($id) {
            $model->delete($id);
        }
        return redirect()->to('/bareme');
    }

    public function edit($id){
        $model = new BaremeFraisModel();
        $typesOperationModel = new TypesOperationModel();
        $data = [
            'bareme'          => $model->find($id),
            'typesOperations' => $typesOperationModel->findAll(),
        ];
        return view('bareme/form', $data);
    }

    public function update($id){
        $model = new BaremeFraisModel();
        $data = [
            'type_operation_id' => $this->request->getPost('type_operation_id'),
            'montant_min'       => $this->request->getPost('montant_min'),
            'montant_max'       => $this->request->getPost('montant_max'),
            'frais'             => $this->request->getPost('frais'),
            'actif'             => $this->request->getPost('actif'),
        ];
        $model->update($id, $data);
        return redirect()->to('/bareme');
    }
    // Affiche le formulaire de test
    public function testFrais(){
        $typesOperationModel = new TypesOperationModel();
        return view('bareme/test_frais', [
            'typesOperations' => $typesOperationModel->findAll(),
        ]);
    }

    // Endpoint JSON appelé par le JS
    public function apiFrais(){
        $typeCode = $this->request->getGet('type_code');
        $montant  = (int) $this->request->getGet('montant');
        $frais    = (new BaremeFraisModel())->getFrais($typeCode, $montant);

        return $this->response->setJSON(['frais' => $frais]);
    }
}
