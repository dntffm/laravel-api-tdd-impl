<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSubSection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'video_url',
        'type',
        'section_id'
    ];
}
