<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Setting::create([
            'app_name'=>'NEOMED APP',
            'app_logo'=>url('storage/WhatsApp Image 2023-02-20 at 10.38.09 PM.png'),
            'app_slogon'=>'NEOMED APP',
            'app_description'=>'NEOMED APP Description',
            'email'=>'NEOMED APP',
            'customer_service_number'=>'NEOMED APP',
            'post_address'=>'saudi arabic mekka',
            'ministery_licence'=>'554445454',
            'signature_image'=>url('storage/WhatsApp Image 2023-02-20 at 10.38.09 PM.png'),
        ]);
    }

}
