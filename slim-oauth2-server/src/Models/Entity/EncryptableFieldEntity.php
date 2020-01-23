<?php

namespace App\Models\Entity;

class EncryptableFieldEntity
{
    protected $hashOptions = ['const' => 11];

    /**
     * Password Hash
     * 
     * @param string $value
     * @return string
     */
    protected function encryptField(string $value): string
    {
        return password_hash($value, PASSWORD_BCRYPT, $this->hashOptions);
    }

    /**
     * Verify Password
     * 
     * @param string $encryptedValue
     * @param string $value
     * @return boolean
     */
    public function verifyEncryptedFieldValue($encryptedValue, $value): bool
    {
        return password_verify($value, $encryptedValue);
    }
}