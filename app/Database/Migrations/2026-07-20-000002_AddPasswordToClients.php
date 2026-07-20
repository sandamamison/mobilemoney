<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPasswordToClients extends Migration
{
    public function up()
    {
        // Ajouter le champ password à la table clients
        $this->forge->addColumn('clients', [
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'telephone'
            ],
        ]);
    }

    public function down()
    {
        // Supprimer la colonne password
        $this->forge->dropColumn('clients', 'password');
    }
}
