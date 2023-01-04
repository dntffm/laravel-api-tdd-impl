<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_name',
        'description',
        'price',
        'discount',
        'thumbnail',
        'course_category'
    ];

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category', 'id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_courses')
        ->wherePivot('user_id', Auth::user()->id)
        ->wherePivot('status', 'active');
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class);
    }

    public function image()
    {
        return $this->hasOne(File::class, 'id', 'thumbnail');
    }
}
