<?php

namespace Database\Factories;

use App\Models\CourseSubSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseSection>
 */
class CourseSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'section_name' => $this->faker->name,
            'sub_sections' => CourseSubSection::factory()
        ];
    }
}
