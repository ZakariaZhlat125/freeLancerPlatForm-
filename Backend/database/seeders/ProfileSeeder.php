<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Profile::create([
            'user_id' => 1,
            // 'user_id' => User::all()->random()->id,
            'name' => 'متاح',
            'gender' => 'male',
            'mobile' => '773065471',
            'country' => 'syria',

            'account_type' => 'provider',
            'job_title' => 'backend developer',
            'specialization' => 'platform',
            'bio' => '"',
        ]);

        $Dhoha = Profile::create([
            'user_id' => 2,
            // 'user_id' => User::all()->random()->id,
            'name' => 'فادي خوري ',
            'gender' => 'male',
            'mobile' => '0934522844',
            'country' => 'syria',

            'account_type' => 'provider',
            'job_title' => 'backend developer',
            'specialization' => 'backend developer',
            'bio' => 'إنه قارئ ومفسر ومبدع في آنٍ واحد.. ذلك هو المترجم .

            مترجم محترف على مستوى عالي من الدقة والمهارة. خريج بكالوريوس لغة انجليزية وترجمة وحاصل على شهادة التوفل وتدريبات مكثفة في اللغة الانجليزية والترجمة. امتلك من الخبرة مايزيد عن 12 عام في هذا المجال.

            لا اتقدم لاي مشروع الا اذا كنت واثق انني استطيع انجازه باحترافية بنسبة 100%. انا مواظب جدا واهتم بالتفاصيل الصغيرة لاي عمل انجزه.

            الخدمات التي اقدمها:

            *الترجمة من اللغة العربية <> اللغة الانجليزية.

            *التدقيق اللغوي.

            *اعمال التلخيص واعادة الصياغة من والى اللغتين.

            *التفريغ الصوتي.

            *الترجمة المرئية Subtitles.
            انه لمن دواعي سروري ان انجز مشاريعك باقل وقت ممكن وعلى درجة عالية من الدقة والمهنية.

            رضا الزبائن هو هدفي الاول واضمن لك تسليمك العمل في اقرب وقت وبافضل نتيجة.
            تواصل معي عبر الرسائل لمناقشة تفاصيل مشروعك.',
        ]);

       
    }
}
