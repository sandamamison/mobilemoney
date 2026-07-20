<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PrefixeAutreOperateurModel;
use App\Models\AutreOperateurModel;

class PrefixeAutreOperateurController extends BaseController
{
    /**
     * Liste tous les préfixes externes avec l'opérateur associé.
     */
    public function index()
    {
        $model = new PrefixeAutreOperateurModel();
        $data = [
            'prefixes' => $model->getAllWithOperateur(),
        ];
        return view('prefixes_externes/index', $data);
    }

    /**
     * Formulaire de création d'un préfixe externe.
     */
    public function create()
    {
        $operateurModel = new AutreOperateurModel();
        $data = [
            'operateurs' => $operateurModel->orderBy('nom', 'ASC')->findAll(),
        ];
        return view('prefixes_externes/form', $data);
    }

    /**
     * Enregistre un nouveau préfixe externe.
     */
    public function store()
    {
        $model   = new PrefixeAutreOperateurModel();
        $prefixe = trim($this->request->getPost('prefixe'));

        // Vérification doublon avant insertion
        if ($model->prefixeExiste($prefixe)) {
            return redirect()->back()->withInput()
                ->with('errors', ['prefixe' => 'Ce préfixe existe déjà.']);
        }

        $data = [
            'prefixe'            => $prefixe,
            'autre_operateur_id' => (int) $this->request->getPost('autre_operateur_id'),
            'actif'              => 1,
        ];

        if (!$model->insert($data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/prefixes-externes')->with('success', 'Préfixe ajouté avec succès.');
    }

    /**
     * Formulaire de modification d'un préfixe.
     */
    public function edit(int $id)
    {
        $model          = new PrefixeAutreOperateurModel();
        $operateurModel = new AutreOperateurModel();

        $prefixe = $model->find($id);
        if (!$prefixe) {
            return redirect()->to('/prefixes-externes')->with('error', 'Préfixe introuvable.');
        }

        $data = [
            'prefixe'    => $prefixe,
            'operateurs' => $operateurModel->orderBy('nom', 'ASC')->findAll(),
        ];
        return view('prefixes_externes/edit', $data);
    }

    /**
     * Met à jour un préfixe (prefixe + opérateur associé).
     */
    public function update(int $id)
    {
        $model   = new PrefixeAutreOperateurModel();
        $prefixe = trim($this->request->getPost('prefixe'));

        // Vérification doublon (sauf l'enregistrement courant)
        if ($model->prefixeExiste($prefixe, $id)) {
            return redirect()->back()->withInput()
                ->with('errors', ['prefixe' => 'Ce préfixe est déjà utilisé par un autre enregistrement.']);
        }

        $data = [
            'prefixe'            => $prefixe,
            'autre_operateur_id' => (int) $this->request->getPost('autre_operateur_id'),
        ];

        if (!$model->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $model->errors());
        }

        return redirect()->to('/prefixes-externes')->with('success', 'Préfixe mis à jour avec succès.');
    }

    /**
     * Active ou désactive un préfixe (toggle).
     */
    public function toggleActif(int $id)
    {
        $model = new PrefixeAutreOperateurModel();

        if (!$model->toggleActif($id)) {
            return redirect()->to('/prefixes-externes')->with('error', 'Impossible de modifier le statut de ce préfixe.');
        }

        return redirect()->to('/prefixes-externes')->with('success', 'Statut du préfixe mis à jour.');
    }

    /**
     * Supprime un préfixe externe.
     */
    public function delete()
    {
        $model = new PrefixeAutreOperateurModel();
        $id    = (int) $this->request->getPost('id');

        $model->delete($id);

        return redirect()->to('/prefixes-externes')->with('success', 'Préfixe supprimé.');
    }
}
