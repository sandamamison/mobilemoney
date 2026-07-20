<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table      = 'accounts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_id',
        'account_number',
        'balance',
        'currency',
        'account_type',
        'is_active',
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $validationRules    = [
        'user_id'       => 'required|is_not_empty',
        'account_number' => 'required|is_unique[accounts.account_number]',
        'balance'       => 'permit_empty|numeric',
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    // Relations
    public function user()
    {
        return $this->belongsTo('App\Models\UserModel', 'user_id', 'id');
    }

    /**
     * Obtenir le compte par ID utilisateur
     */
    public function getAccountByUserId($userId)
    {
        return $this->where('user_id', $userId)->where('is_active', 1)->first();
    }

    /**
     * Générer un numéro de compte unique
     */
    public static function generateAccountNumber()
    {
        $prefix = 'ACC';
        $timestamp = time();
        $random = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $timestamp . $random;
    }

    /**
     * Obtenir le solde du compte
     */
    public function getBalance($accountId)
    {
        $account = $this->find($accountId);
        return $account ? $account['balance'] : 0;
    }

    /**
     * Mettre à jour le solde
     */
    public function updateBalance($accountId, $amount)
    {
        $account = $this->find($accountId);
        if ($account) {
            $newBalance = $account['balance'] + $amount;
            $this->update($accountId, ['balance' => $newBalance]);
            return $newBalance;
        }
        return false;
    }

    /**
     * Vérifier si le compte a suffisamment de fonds
     */
    public function hasSufficientFunds($accountId, $amount)
    {
        $balance = $this->getBalance($accountId);
        return $balance >= $amount;
    }
}
