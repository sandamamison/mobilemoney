<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TypesOperationModel;
use App\Models\OperationModel;
class GainController extends BaseController
{
    public function index()
    {
        $operationModel = new OperationModel();

        $data = [
            'gainsParType' => $operationModel->gainsParType(),
            'gainGlobal'   => $operationModel->totalgain(),
        ];

        return view('gain/index', $data);
    }

    public function historique()
    {
        $db = db_connect();
        $typesOperationModel = new TypesOperationModel();

        $date = trim((string) $this->request->getGet('date'));
        $type = trim((string) $this->request->getGet('type'));
        $numero = trim((string) $this->request->getGet('numero'));

        $sql = "SELECT
                    o.reference,
                    o.date_operation,
                    o.montant,
                    o.frais,
                    o.statut,
                    t.code AS type_code,
                    t.libelle AS type_libelle,
                    COALESCE(cs.telephone, cd.telephone) AS numero_principal,
                    CASE
                        WHEN cs.telephone IS NOT NULL AND cd.telephone IS NOT NULL THEN cs.telephone || ' -> ' || cd.telephone
                        ELSE COALESCE(cs.telephone, cd.telephone)
                    END AS numeros_concernes
                FROM operations o
                JOIN types_operations t ON t.id = o.type_operation_id
                LEFT JOIN comptes csource ON csource.id = o.compte_source_id
                LEFT JOIN clients cs ON cs.id = csource.client_id
                LEFT JOIN comptes cdestination ON cdestination.id = o.compte_destination_id
                LEFT JOIN clients cd ON cd.id = cdestination.client_id
                WHERE o.statut = 'VALIDEE'";

        $params = [];

        if ($date !== '') {
            $sql .= ' AND DATE(o.date_operation) = :date:';
            $params['date'] = $date;
        }

        if ($type !== '') {
            $sql .= ' AND t.code = :type:';
            $params['type'] = strtoupper($type);
        }

        if ($numero !== '') {
            $sql .= ' AND (cs.telephone LIKE :numero: OR cd.telephone LIKE :numero:)';
            $params['numero'] = '%' . $numero . '%';
        }

        $sql .= ' ORDER BY o.date_operation DESC, o.id DESC';

        $historique = $db->query($sql, $params)->getResultArray();

        $data = [
            'historique' => $historique,
            'types'      => $typesOperationModel->orderBy('libelle', 'ASC')->findAll(),
            'filtres'    => [
                'date'   => $date,
                'type'   => $type,
                'numero' => $numero,
            ],
        ];

        return view('gain/historique', $data);
    }
}
