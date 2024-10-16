<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = "assignments";

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'end_date',
        'max_grade',
        'status',
        'attempts_allowed',
        'is_group_assignment',
        'course_id'
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'due_date' => 'datetime',
        'end_date' => 'datetime',
        'max_grade' => 'float',
        'status' => 'integer',
        'attempts_allowed' => 'integer',
        'is_group_assignment' => 'boolean',
        'course_id' => 'integer',
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'assignment_id');
    }
}
