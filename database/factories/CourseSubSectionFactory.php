<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseSubSection>
 */
class CourseSubSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->name,
            'video_url' => $this->faker->domainName(),
            'type' => $this->faker->randomElement(['quiz', 'video']),
            'questions' => [
                'question' => Quiz::factory(),
                'answers' => QuizAnswer::factory()
            ] 
        ];
    }
}
