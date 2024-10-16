<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionLog extends Model
{
    use HasFactory;

    protected $table = 'submission_logs';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'submitted_at',
        'response_id',
        'status',
    ];

    protected $casts = [
        'assignment_id' => 'integer',
        'student_id' => 'integer',
        'submitted_at' => 'datetime',
        'response_id' => 'integer',
        'status' => 'string',
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

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function updateStatus(string $status): bool
    {
        $this->status = $status;
        return $this->save();
    }
}
