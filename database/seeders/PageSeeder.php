<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //about us page
        Page::create([
            'title'=>'About Us',
            'sub_title'=>'About',
            'slug'=>'about-us',
            'content'=>'<p class="text-white sm:text-xl sm:leading-relaxed">
                NEOMED It is a digital health service, for beneficiaries who use its medical services
                It also aims to facilitate communication between workers and medical service providers with the goal of a secure Internet environment.
            </p>
            <p class="text-white sm:text-xl sm:leading-relaxed">
            NEOMED It is a digital health service, for beneficiaries who use its medical services
            It also aims to facilitate communication between workers and medical service providers with the goal of a secure Internet environment.
            </p>
          ',
            'is_publishable'=>true,
            'language_id'=>1,
        ]);
        //about us page
        Page::create([
            'title'=>'Privacy Pollicy',
            'sub_title'=>'privacy',
            'slug'=>'privacy-policy',
            'content'=>'<p class="text-white sm:text-xl sm:leading-relaxed">
                NEOMED It is a digital health service, for beneficiaries who use its medical services
                It also aims to facilitate communication between workers and medical service providers with the goal of a secure Internet environment.
            </p>
            <p class="text-white sm:text-xl sm:leading-relaxed">
            NEOMED It is a digital health service, for beneficiaries who use its medical services
            It also aims to facilitate communication between workers and medical service providers with the goal of a secure Internet environment.
            </p>
          ',
            'is_publishable'=>true,
            'language_id'=>1,
        ]);

    }
}
