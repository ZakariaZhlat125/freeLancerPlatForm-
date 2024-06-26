<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          //
          $fadi = User::create([
            'name' => ' fadi khoury ',
            'email' => 'fadikhoury@gmail.com',
            'password' => Hash::make('fadikhoury@22?!'),
            'remember_token' => Str::random(60),
        ]);
        $fadi->addRole('provider');

        $john = User::create([
            'name' => "john",
            'email' => 'john@gmail.com',
            'password' => Hash::make('john@22?!'),
            'remember_token' => Str::random(60),
        ]);
        $john->addRole('seeker');

    }
}
