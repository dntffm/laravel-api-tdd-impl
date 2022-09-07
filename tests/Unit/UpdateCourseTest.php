<?php

use function Pest\Laravel\putJson;

uses(Tests\TestCase::class);
$token = "2|Nwayc7DFCi08gbPsMqcxAOvO9PvgJKonRP6JPvYL";

test('update course with valid data', function () use($token) {
    putJson(
        'api/courses/9',
        [
            'course_name' => 'Java Programming part II',
            'description' => 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32',
            'price' => 100000,
            'discount' => 0,
            'thumbnail' => 1,
            'course_category' => 1,
        ],
        [
            'Authorization' => 'Bearer '.$token
        ]
    )
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'message',
    ])
    ->assertStatus(200);
});

