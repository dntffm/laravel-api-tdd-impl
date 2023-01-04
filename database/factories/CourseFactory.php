<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'course_name' => $this->faker->name,
            'description' => $this->faker->text,
            'price' => $this->faker->numberBetween(10000, 100000),
            'discount' => $this->faker->numberBetween(0, 100)
        ];
    }
}
