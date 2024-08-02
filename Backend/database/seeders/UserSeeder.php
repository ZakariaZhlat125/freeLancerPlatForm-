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
        $Zakaria = User::create([
            'name' => ' زكريا زحلط',
            'email' => 'zakariaZhlat@gmail.com',
            'password' => Hash::make('zakaria@22?!'),
            'remember_token' => Str::random(60),
        ]);
        $Zakaria->addRole('provider');
        $Yazen = User::create([
            'name' => 'يزن وسوف',
            'email' => 'yazenwassof@gmail.com',
            'password' => Hash::make('yazen@22?!'),
            'remember_token' => Str::random(60),
        ]);
        $Yazen->addRole('provider');

        $Batoul = User::create([
            'name' => 'بتول جديد',
            'email' => 'batoulJdeed@gmail.com',
            'password' => Hash::make('batoul@22?!'),
            'remember_token' => Str::random(60),
        ]);

        $Batoul->addRole('seeker');
    }
}
