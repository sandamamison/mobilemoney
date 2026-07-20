<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComptesModel;
use App\Models\ClientModel;
use App\Models\OperationModel;

class CompteController extends BaseController
{
    public function index()
    {
        $sql = "SELECT co.id, co.solde, co.statut, co.date_creation,
                       cl.telephone, cl.nom
                FROM comptes co
                JOIN clients cl ON cl.id = co.client_id
                ORDER BY co.id DESC";

        $comptes = db_connect()->query($sql)->getResultArray();
        return view('comptes/index', compact('comptes'));
    }

    public function bloque($id)
    {
        (new ComptesModel())->update($id, ['statut' => 'BLOQUE']);
        return redirect()->to('/comptes');
    }

    public function debloque($id)
    {
        (new ComptesModel())->update($id, ['statut' => 'ACTIF']);
        return redirect()->to('/comptes');
    }

    public function show($id)
    {
        $db = db_connect();

        $compte = $db->query("
            SELECT co.id, co.solde, co.statut, co.date_creation,
                   cl.telephone, cl.nom
            FROM comptes co
            JOIN clients cl ON cl.id = co.client_id
            WHERE co.id = ?
        ", [$id])->getRowArray();

        $operations = $db->query("
            SELECT o.reference, o.montant, o.frais, o.statut, o.date_operation,
                   t.libelle AS type_libelle
            FROM operations o
            JOIN types_operations t ON t.id = o.type_operation_id
            WHERE o.compte_source_id = ? OR o.compte_destination_id = ?
            ORDER BY o.date_operation DESC
        ", [$id, $id])->getResultArray();

        return view('comptes/show', compact('compte', 'operations'));
    }
}
