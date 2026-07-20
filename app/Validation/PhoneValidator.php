<?php

namespace App\Validation;

use App\Models\PrefixeModel;

/**
 * Classe pour valider les numéros de téléphone
 */
class PhoneValidator
{
    /**
     * Normaliser un numéro de téléphone vers le format local accepté par la base.
     *
     * @param string $phoneNumber
     * @return string
     */
    private static function normalizePhoneNumber($phoneNumber)
    {
        $cleanedPhone = preg_replace('/[^0-9+]/', '', $phoneNumber);

        if (strpos($cleanedPhone, '+') === 0) {
            return $cleanedPhone;
        }

        return $cleanedPhone;
    }

    /**
     * Valider le format et le préfixe du numéro de téléphone
     * @param string $phoneNumber
     * @return bool
     */
    public static function validatePhoneNumber($phoneNumber)
    {
        $normalizedPhone = self::normalizePhoneNumber($phoneNumber);

        if (!preg_match('/^\d{8}$/', $normalizedPhone)
            && !preg_match('/^\d{10}$/', $normalizedPhone)
            && !preg_match('/^\+\d{8,20}$/', $normalizedPhone)) {
            return false;
        }

        return true;
    }

    /**
     * Valider le préfixe du numéro de téléphone
     * @param string $phoneNumber
     * @return bool
     */
    public static function validatePhonePrefix($phoneNumber)
    {
        $normalizedPhone = self::normalizePhoneNumber($phoneNumber);

        if (preg_match('/^\d{3}/', $normalizedPhone, $matches)) {
            $prefix = $matches[0];
        } elseif (preg_match('/^\+(\d{3})/', $normalizedPhone, $matches)) {
            $prefix = $matches[1];
        } else {
            return false;
        }

        $prefixModel = new PrefixeModel();

        return $prefixModel->isPrefixActif($prefix);
    }

    /**
     * Nettoyer et formater le numéro de téléphone
     * @param string $phoneNumber
     * @return string
     */
    public static function formatPhoneNumber($phoneNumber)
    {
        return self::normalizePhoneNumber($phoneNumber);
    }

    /**
     * Obtenir la liste de tous les pays supportés
     * @return array
     */
    public static function getSupportedCountries()
    {
        $prefixModel = new PrefixeModel();
        return $prefixModel->getActifs();
    }

    /**
     * Valider le numéro de téléphone selon les règles complètes
     * @param string $phoneNumber
     * @return array ['valid' => bool, 'message' => string, 'formatted' => string]
     */
    public static function validate($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return [
                'valid'     => false,
                'message'   => 'Le numéro de téléphone est requis',
                'formatted' => null,
            ];
        }

        if (!self::validatePhoneNumber($phoneNumber)) {
            return [
                'valid'     => false,
                'message'   => 'Format du numéro de téléphone invalide',
                'formatted' => null,
            ];
        }

        if (!self::validatePhonePrefix($phoneNumber)) {
            return [
                'valid'     => false,
                'message'   => 'Le préfixe du numéro de téléphone n\'est pas supporté',
                'formatted' => null,
            ];
        }

        $formatted = self::formatPhoneNumber($phoneNumber);

        return [
            'valid'     => true,
            'message'   => 'Numéro de téléphone valide',
            'formatted' => $formatted,
        ];
    }
}
