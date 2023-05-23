<?php

namespace Database\Factories;

use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubSpeciality>
 */
class SubSpecialityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name,
            'slug'=>$this->faker->slug,
            'speciality_id'=>function () {
                return Speciality::find(1)?->id ?? Speciality::factory()->create()->id;
            }
        ];
    }
}
