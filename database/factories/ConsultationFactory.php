<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Consultation>
 */
class ConsultationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id'=>Doctor::first() ?? Doctor::factory(1)->create()->id,
            'patient_id'=>Patient::first() ?? Patient::factory(1)->create()->id,
            'start_time'=>Carbon::now(),
            'end_time'=>Carbon::now(),
            'status'=>$this->faker->randomElement(['pending','in_progress','incompleted','completed','canceled','rejected']),
        ];
    }
}
