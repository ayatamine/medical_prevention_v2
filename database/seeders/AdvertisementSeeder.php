<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Advertisement::create([
            'title'=>'ads title 1',
            'title_ar'=>'عنوان اعلان',
            'image'=> 'ad-image.png',
            'text' =>'some text for the ads',
            'text_ar'=>'بعض النص لوصف الاعلان',
            'duration'=>5,
            'publish_date'=>Carbon::today()
        ]);
    }
}
