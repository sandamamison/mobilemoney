<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PrefixeModel;

class PrefixeController extends BaseController
{
    public function index()
    {
        $model = new PrefixeModel();
        $prefixe = $model->findAll();
        $data = ['prefixe'=>$prefixe];
        return view('prefixe/index',$data);
    }

    public function create(){
        return view('prefixe/form');
    }

    public function store(){
        $model = new PrefixeModel();
        $data = [
            'prefixe'=>$this->request->getPost('prefixe')
        ];
        $model->insert($data);
        return redirect()->to('/prefixe');
    }

    public function delete(){
        $model = new PrefixeModel();
        $id = $this->request->getPost('id');
        $model->delete($id);
        return redirect()->to('/prefixe');
    }
}
