<?php

namespace Database\Seeders;

use App\Models\Scale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Scale::create([
        'title'=>'Anxiety Scale',
        'title_ar'=>'مقياس القلق',
        'short_description'=>'Over the last six months how......?',
        'short_description_ar'=>' في خلال الاشهر  الماضية ماهي الاشياء التي ..... ؟',
       ]);
       Scale::create([
        'title'=>'Depression Scale',
        'title_ar'=>'مقياس الإكتئاب',
        'short_description'=>'Over the last six months how......?',
        'short_description_ar'=>' في خلال الاشهر  الماضية ماهي الاشياء التي ..... ؟',
       ]);
    }
}
