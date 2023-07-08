<?php

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseSection;
use App\Models\File;
use App\Models\User;

use function Pest\Laravel\actingAs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

//uses(RefreshDatabase::class);

test('admin dapat menambah course baru', function() {
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $courseCategory = CourseCategory::factory()->create();

    $course = [
        "course_name" => 'Tes',
        'description' => 'description',
        'price' => 11,
        'discount' => 10,
        'course_category' => $courseCategory->id
    ];
   
    $course['thumbnail'] = UploadedFile::fake()->image('thumbnail.jpg');
    $course['course_sections'] = [
        [
            'section_name' => 'Test'
        ]
    ];
    
    $course['course_sections'][0]['sub_sections'] = [
        [
            'title' => 'Tes',
            'type' => 'quiz',
            'questions' => [
                [
                    'question' => 'lorem ipsum',
                    'answers' => [
                        [
                            'answer' => 'A',
                            'is_correct' => true,
                        ],
                        [
                            'answer' => 'B',
                            'is_correct' => false,
                        ],
                    ]
                ]
            ]
        ],
        [
            'title' => 'Tes',
            'type' => 'video',
            'video_url' => 'google.com'
        ], 
    ];
    
    actingAs($user)
    ->postJson(route('admin.course.store'), $course)
    ->assertJson(['success' => true])
    ->assertStatus(200);
});

test('admin tidak dapat menambah course baru dengan invalid data', function() {
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $courseCategory = CourseCategory::factory()->create();

    $course = Course::factory()->make([
        //'course_category' => $courseCategory->id,
    ]);
   
    $course['thumbnail'] = UploadedFile::fake()->image('thumbnail.jpg');
    $course['course_sections'] = CourseSection::factory()->count(3)->make([
        'course_id' => $course->id,
    ])->toArray();

    actingAs($user)
    ->postJson(route('admin.course.store'), $course->toArray())
    ->assertStatus(422);
});

test('admin dapat melihat daftar course', function() {
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $courseCategory = CourseCategory::factory()->create();
    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg')->storeAs('public/course', 'thumbnail.jpg');
    $file = File::factory()->create([
        'url' => $thumbnail,
        'type' => 'image'
    ]);

    $course = Course::factory()->create([
        'course_category' => $courseCategory->id,
        'thumbnail' => $file->id
    ]);

    CourseSection::factory()->count(3)->create([
        'course_id' => $course->id
    ]);

    actingAs($user)
    ->getJson(route('admin.course.index'))
    ->assertStatus(200);
});

test('admin dapat melihat detail course', function() {
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $courseCategory = CourseCategory::factory()->create();
    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg')->storeAs('public/course', 'thumbnail.jpg');
    $file = File::factory()->create([
        'url' => $thumbnail,
        'type' => 'image'
    ]);

    $course = Course::factory()->create([
        'course_category' => $courseCategory->id,
        'thumbnail' => $file->id
    ]);

    CourseSection::factory()->count(3)->create([
        'course_id' => $course->id
    ]);

    actingAs($user)
    ->getJson(route('admin.course.show', $course->id))
    ->assertStatus(200);
});

test('admin tidak dapat menghapus course dengan id yang salah', function(){
    $user = User::factory()->create([
        'role' => '1'
    ]);

    actingAs($user)
    ->deleteJson(route('admin.course.destroy', 11111))
    ->assertStatus(404);
});

test('admin dapat menghapus course dengan valid id', function() {
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $courseCategory = CourseCategory::factory()->create();
    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg')->storeAs('public/course', 'thumbnail.jpg');
    $file = File::factory()->create([
        'url' => $thumbnail,
        'type' => 'image'
    ]);

    $course = Course::factory()->create([
        'course_category' => $courseCategory->id,
        'thumbnail' => $file->id
    ]);

    CourseSection::factory()->count(3)->create([
        'course_id' => $course->id
    ]);

    actingAs($user)
    ->deleteJson(route('admin.course.destroy', $course->id))
    ->assertStatus(200);
});

test('admin dapat mengubah course dengan valid id', function() {
    $this->withoutExceptionHandling();
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $courseCategory = CourseCategory::factory()->create();
    $thumbnail = UploadedFile::fake()->image('thumbnail.jpg')->storeAs('public/course', 'thumbnail.jpg');
    $file = File::factory()->create([
        'url' => $thumbnail,
        'type' => 'image'
    ]);

    $course = Course::factory()->create([
        'course_category' => $courseCategory->id,
        'thumbnail' => $file->id
    ]);

    $course['course_sections'] = CourseSection::factory()->count(3)->create([
        'course_id' => $course->id
    ]);
    $course['thumbnail'] = UploadedFile::fake()->image('thumbnailUpdated.jpg');
    $course['course_name'] = 'Course Name';

    actingAs($user)
    ->putJson(route('admin.course.destroy', $course->id), $course->toArray())
    ->assertStatus(200);
});

