<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\UserKey;
use App\Models\Role;


class UserKeysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $keyPaths = [
            'admin' => [
                'public' => storage_path('keys/admin/public_key_admin.pem'),
            ],
            'freelancer' => [
                'public' => storage_path('keys/freelancer/public_key_freelancer.pem'),
            ],
            'seeker' => [
                'public' => storage_path('keys/seeker/public_key_seeker.pem'),
            ],
        ];

        $roles = Role::whereIn('name', ['admin', 'freelancer', 'seeker'])->get();

        foreach ($roles as $role) {
            // Fetch users associated with the current role
            $users = $role->users; // Assumes you have a relationship method 'users' in the Role model

            foreach ($users as $user) {
                if (isset($keyPaths[$role->name])) {
                    // Read the public key from the file
                    $publicKey = file_get_contents($keyPaths[$role->name]['public']);

                    // Encrypt the public key
                    $encryptedPublicKey = Crypt::encryptString($publicKey);

                    // Save the encrypted public key to the user_keys table
                    UserKey::updateOrCreate(
                        ['user_id' => $user->id],
                        ['public_key' => $encryptedPublicKey]
                    );
                }
            }
        }
    }
}
