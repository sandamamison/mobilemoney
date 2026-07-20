<?php

namespace App\Models;

use CodeIgniter\Model;

class PhonePrefixModel extends Model
{
    protected $table      = 'phone_prefixes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'country_code',
        'country_name',
        'prefix',
        'operator_name',
        'min_length',
        'max_length',
        'is_active',
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $validationRules    = [
        'country_code' => 'required|is_unique[phone_prefixes.country_code]',
        'prefix'       => 'required',
    ];

    /**
     * Obtenir tous les préfixes actifs
     */
    public function getActivePrefixes()
    {
        return $this->where('is_active', 1)->findAll();
    }

    /**
     * Obtenir un préfixe par le code du pays
     */
    public function getPrefixByCountryCode($countryCode)
    {
        return $this->where('country_code', $countryCode)->where('is_active', 1)->first();
    }

    /**
     * Obtenir un préfixe par le code téléphonique
     */
    public function getPrefixByPhoneCode($phoneCode)
    {
        return $this->where('prefix', $phoneCode)->where('is_active', 1)->first();
    }
}
