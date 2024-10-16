<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $table = "submissions";

    protected $fillable = [
        'submitted_at',
        'grade',
        'feedback',
        'assignment_id',
        'student_id',
        'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'grade' => 'float',
        'feedback' => 'string',
        'assignment_id' => 'integer',
        'student_id' => 'integer',
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}
