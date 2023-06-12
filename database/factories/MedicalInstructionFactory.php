<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalInstruction>
 */
class MedicalInstructionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // return [
        //     'title'=>$this->faker->title(),
        //     'title_ar'=>'هنا مثال لعنوان',
        //     'image'=>'medical_instruction/image.png',
        //     'content'=>$this->faker->text(),
        //     'content_ar'=>$this->faker->text(),
        //  ];
        return [
            'title' =>$this->faker->title,
            'title_ar' =>$this->faker->title,
            'image' =>'https://ui-avatars.com/api/?background=0D8ABC&color=fff',
            'content' =>$this->faker->title,
            'content_ar' =>$this->faker->title,
            'publish_date' =>Carbon::now(),
            'duration' => random_int(1,5)
        ];
    }
}
