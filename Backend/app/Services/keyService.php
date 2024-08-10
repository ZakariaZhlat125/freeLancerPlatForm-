<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class KeyService
{
    /**
     * Generate RSA key pair and store only the encrypted public key in the database.
     */
    public function generateAndStorePublicKey($userId)
    {
        // Generate a new private (and public) key pair
        $keyResource = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        // Extract the private key from the resource (we won't save it)
        openssl_pkey_export($keyResource, $privateKey);

        // Extract the public key
        $publicKeyDetails = openssl_pkey_get_details($keyResource);
        $publicKey = $publicKeyDetails['key'];

        // Encrypt the public key before storing it
        $encryptedPublicKey = Crypt::encrypt($publicKey);

        // Store the encrypted public key in the user_keys table
        \DB::table('user_keys')->insert([
            'user_id' => $userId,
            'public_key' => $encryptedPublicKey,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // You may also want to securely discard the private key from memory
        unset($privateKey);
    }
}
