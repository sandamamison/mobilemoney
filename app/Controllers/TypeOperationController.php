<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TypesOperationModel;

class TypeOperationController extends BaseController
{
    public function index()
    {
        $model = new TypesOperationModel();
        $tous = $model->findAll();
        $data = [
            'typesoperation'=>$tous,
        ];
        return view('typesoperation/dashboard', $data);
    }
    
    public function create(){
        return view('typesoperation/form');
    }
    public function store(){
        $model = new TypesOperationModel();
        $data = [
            'code' => strtoupper($this->request->getPost('code')),
            'libelle' => $this->request->getPost('libelle'),
            'avec_frais' => $this->request->getPost('avec_frais'),
            'actif' => $this->request->getPost('actif'),
        ];
        $model->insert($data);
        return redirect()->to('/typesoperation');
    }

    public function delete(){
        $model = new TypesOperationModel();
        $id = $this->request->getPost('id');
        if($id) {
            $model->delete($id);
        }
        return redirect()->to('/typesoperation');
    }

    public function edit($id){
        $model = new TypesOperationModel();
        $type = $model->find($id);
        $data = [
            'type'=>$type,
        ];
        return view('typesoperation/form', $data);
    }

    public function update($id){
        $model = new TypesOperationModel();
        $data = [
            'libelle' => $this->request->getPost('libelle'),
            'avec_frais' => $this->request->getPost('avec_frais'),
            'actif' => $this->request->getPost('actif'),
        ];
        $model->update($id, $data);
        return redirect()->to('/typesoperation');
    }
}
