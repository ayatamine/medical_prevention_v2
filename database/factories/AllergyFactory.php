<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Allergy;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Allergy>
 */
class AllergyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name(),
            'name_ar'=>$this->faker->name()
        ];
    }
}
