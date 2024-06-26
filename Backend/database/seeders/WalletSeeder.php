<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $Admin = Wallet::create([
            'holder_type' => 'Admin',
            'holder_id' => 1,
            'name' => 'Admin Wallet',
            'slug' => 'default',
            'uuid' => Uuid::uuid4()->toString(),
            'balance' => 10000,
            'decimal_places'=>2,
        ]);
    }
}
