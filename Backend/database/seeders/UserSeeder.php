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
        $Dhoha = User::create([
            'name' => 'فادي خوري',
            'email' => 'fadikhory@gmail.com',
            'password' => Hash::make('fadi@22?!'),
            'remember_token' => Str::random(60),
        ]);
        $Dhoha->addRole('provider');


        $Roqaiah = User::create([
            'name' => 'شادي خوري',
            'email' => 'shadiKhoruy@gmail.com',
            'password' => Hash::make('shadi@22?!'),
            'remember_token' => Str::random(60),
        ]);
        $Roqaiah->addRole('seeker');
    }
}
