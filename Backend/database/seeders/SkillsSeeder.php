<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Skill::updateOrCreate(['name' => 'فتشوب'], ['is_active' => 1]);
        Skill::updateOrCreate(['name' => 'التصميم الابداعي'], ['is_active' => 1]);
        Skill::updateOrCreate(['name' => 'اعلان'], ['is_active' => 0]);
        Skill::updateOrCreate(['name' => 'مايكروسوفت وورد'], ['is_active' => 1]);
        Skill::updateOrCreate(['name' => 'الترجمة'], ['is_active' => 1]);
    }
}
