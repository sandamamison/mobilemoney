<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'phone_number',
        'first_name',
        'last_name',
        'email',
        'address',
        'is_verified',
        'last_login',
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $validationRules    = [
        'phone_number' => 'required|is_unique[users.phone_number]|valid_phone',
        'email'        => 'permit_empty|valid_email|is_unique[users.email]',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Relations
    public function account()
    {
        return $this->hasOne('App\Models\AccountModel', 'user_id', 'id');
    }

    /**
     * Obtenir un utilisateur par numéro de téléphone
     */
    public function getUserByPhone($phoneNumber)
    {
        return $this->where('phone_number', $phoneNumber)->first();
    }

    /**
     * Mettre à jour le dernier login
     */
    public function updateLastLogin($userId)
    {
        $this->update($userId, [
            'last_login' => date('Y-m-d H:i:s'),
        ]);
    }
}
