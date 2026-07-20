<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PhonePrefixSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Pays d'Afrique de l'Ouest - Format: pays, préfixe, opérateurs
            [
                'country_code' => 'ML',
                'country_name' => 'Mali',
                'prefix'       => '223',
                'operator_name' => 'Orange, Malitel, Chinguitel',
                'min_length'   => 8,
                'max_length'   => 8,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'country_code' => 'SN',
                'country_name' => 'Sénégal',
                'prefix'       => '221',
                'operator_name' => 'Orange, Tigo, Expresso',
                'min_length'   => 9,
                'max_length'   => 9,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'country_code' => 'CI',
                'country_name' => 'Côte d\'Ivoire',
                'prefix'       => '225',
                'operator_name' => 'Orange, MTN, Moov',
                'min_length'   => 8,
                'max_length'   => 8,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'country_code' => 'BJ',
                'country_name' => 'Bénin',
                'prefix'       => '229',
                'operator_name' => 'MTN, Moov, Glo',
                'min_length'   => 8,
                'max_length'   => 8,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'country_code' => 'BF',
                'country_name' => 'Burkina Faso',
                'prefix'       => '226',
                'operator_name' => 'Orange, Telecel, Zain',
                'min_length'   => 8,
                'max_length'   => 8,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'country_code' => 'NE',
                'country_name' => 'Niger',
                'prefix'       => '227',
                'operator_name' => 'Orange, Airtel, MTN',
                'min_length'   => 8,
                'max_length'   => 8,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'country_code' => 'GW',
                'country_name' => 'Guinée-Bissau',
                'prefix'       => '245',
                'operator_name' => 'Orange, MTN-GuinéBissau',
                'min_length'   => 7,
                'max_length'   => 7,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'country_code' => 'GN',
                'country_name' => 'Guinée',
                'prefix'       => '224',
                'operator_name' => 'Orange, Sotelgui, Areeba',
                'min_length'   => 8,
                'max_length'   => 9,
                'is_active'    => 1,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
        ];

        // Insertion par lot pour améliorer les performances
        $this->db->table('phone_prefixes')->insertBatch($data);
    }
}
