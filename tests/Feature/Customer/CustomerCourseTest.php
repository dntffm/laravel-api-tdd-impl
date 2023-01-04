<?php

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use App\Models\UserCourse;

use function Pest\Laravel\actingAs;

test('customer dapat mengambil course dengan valid ID', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '0'
    ]);
    $courseCategory = CourseCategory::factory()->create();
    $course = Course::factory()->create([
        'course_category' => $courseCategory->id
    ]);

    actingAs($user)
    ->postJson(route('customer.course.my-course'), [
        'course_id' => $course->id
    ])
    ->assertStatus(200);
});

test('customer dapat melihat daftar course pribadi', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '0'
    ]);
    $courseCategory = CourseCategory::factory()->create();
    $course = Course::factory()->create([
        'course_category' => $courseCategory->id
    ]);
    UserCourse::create([
        'course_id' => $course->id,
        'user_id' => $user->id,
        'status' => 'active'
    ]);

    actingAs($user)
    ->getJson(route('customer.get.my-course'))
    ->assertStatus(200);
});

test('customer dapat melihat detail tiap course', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '0'
    ]);
    $courseCategory = CourseCategory::factory()->create();
    $course = Course::factory()->create([
        'course_category' => $courseCategory->id
    ]);
    UserCourse::create([
        'course_id' => $course->id,
        'user_id' => $user->id,
        'status' => 'active'
    ]);

    actingAs($user)
    ->getJson(route('customer.show.my-course', $course->id))
    ->assertStatus(200);
});

/* 
test('customer dapat menyimpan progress belajar', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '0'
    ]);

    $subsection = Course::factory()->create();

    $progress = [
        'subsection_id' => $subsection->id,
        'is_finished' => 1
    ];

    actingAs($user)
    ->postJson(route('customer.course.progress.store'), $progress)
    ->assertStatus(200);
}); */

test('customer dapat menyimpan jawaban quiz', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '0'
    ]);

    $quiz = Quiz::factory()->create();
    $quizAnswer = 1;

    $answer = [
        'answer' => $quizAnswer
    ];

    actingAs($user)
    ->putJson(route('customer.save.quiz.answer'), $answer)
    ->assertStatus(200);
});