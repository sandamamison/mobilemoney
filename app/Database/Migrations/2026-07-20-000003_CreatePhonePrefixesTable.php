<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePhonePrefixesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'country_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '3',
                'unique'     => true,
            ],
            'country_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'prefix' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
            ],
            'operator_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'min_length' => [
                'type'       => 'INT',
                'constraint' => 2,
                'default'    => 9,
            ],
            'max_length' => [
                'type'       => 'INT',
                'constraint' => 2,
                'default'    => 9,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', false, true);
        $this->forge->addKey('prefix');
        $this->forge->createTable('phone_prefixes');
    }

    public function down()
    {
        $this->forge->dropTable('phone_prefixes');
    }
}
