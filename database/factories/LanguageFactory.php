<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'label' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'direction' => $this->faker->randomElement(['rtl', 'ltr']),
            'status' => $this->faker->boolean,
            'file' => $this->faker->word,
        ];
    }
}
