<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AutreOperateurModel;

class AutreOperateurController extends BaseController
{
    /**
     * Liste tous les autres opérateurs.
     */
    public function index()
    {
        $model = new AutreOperateurModel();
        $data = [
            'operateurs' => $model->orderBy('nom', 'ASC')->findAll(),
        ];
        return view('autres_operateurs/index', $data);
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('autres_operateurs/form');
    }

    /**
     * Enregistre un nouvel opérateur.
     */
    public function store()
    {
        $model = new AutreOperateurModel();

        $data = [
            'nom'        => trim($this->request->getPost('nom')),
            'commission' => $this->request->getPost('commission'),
            'actif'      => 1,
        ];

        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/autres-operateurs')->with('success', 'Opérateur ajouté avec succès.');
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(int $id)
    {
        $model = new AutreOperateurModel();
        $operateur = $model->find($id);

        if (!$operateur) {
            return redirect()->to('/autres-operateurs')->with('error', 'Opérateur introuvable.');
        }

        $data = ['operateur' => $operateur];
        return view('autres_operateurs/edit', $data);
    }

    /**
     * Met à jour le nom et la commission d'un opérateur.
     */
    public function update(int $id)
    {
        $model = new AutreOperateurModel();

        $data = [
            'nom'        => trim($this->request->getPost('nom')),
            'commission' => $this->request->getPost('commission'),
        ];

        if (!$model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/autres-operateurs')->with('success', 'Opérateur mis à jour avec succès.');
    }

    /**
     * Active ou désactive un opérateur (toggle).
     */
    public function toggleActif(int $id)
    {
        $model = new AutreOperateurModel();

        if (!$model->toggleActif($id)) {
            return redirect()->to('/autres-operateurs')->with('error', 'Impossible de modifier le statut de cet opérateur.');
        }

        return redirect()->to('/autres-operateurs')->with('success', 'Statut de l\'opérateur mis à jour.');
    }

    /**
     * Supprime un opérateur.
     */
    public function delete()
    {
        $model = new AutreOperateurModel();
        $id = (int) $this->request->getPost('id');

        $model->delete($id);

        return redirect()->to('/autres-operateurs')->with('success', 'Opérateur supprimé.');
    }
}
