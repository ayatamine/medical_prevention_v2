<?php

namespace Database\Factories;

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
        return [
            'title'=>$this->faker->title(),
            'title_ar'=>'هنا مثال لعنوان',
            'image'=>'medical_instruction/image.png',
            'content'=>$this->faker->text(),
            'content_ar'=>$this->faker->text(),
         ];
    }
}
