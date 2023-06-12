<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recommendation>
 */
class RecommendationFactory extends Factory
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
            'content'=>$this->faker->text(),
            'content_ar'=>'هنا مثال لوصف سيبم نسيبسيبسيبسيشضقبل',
            'min_age'=>15,
            'max_age'=>25,
            'duration'=>5,
            'publish_date'=>Carbon::today(),
            'sex'=>'male'
        ];
    }
}
