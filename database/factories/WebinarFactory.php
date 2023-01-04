<?php

namespace Database\Factories;

use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Webinar>
 */
class WebinarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'webinar_name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->numberBetween(10000, 100000),
            'link' => $this->faker->url,
            'register_end_at' => '2022-09-14 13:01:00',
            'started_at' => '2022-09-14 13:01:00',
            'accessibility_status' => 'public',
            'status' => 'active',
        ];
    }
}
