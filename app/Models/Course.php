<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory , Searchable;

    protected $table = "courses";

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'max_students',
        'category',
        'teacher_id',
        'status'
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'max_students' => 'integer',
        'category' => 'string',
        'teacher_id' => 'integer',
        'status' => 'integer',
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'course_id');
    }

    // Searchable Methods for Elasticsearch
    public function getSearchIndex(): string
    {
        return 'courses_index';
    }

    public function getElasticsearchFields(): array
    {
        return ['title', 'description', 'category'];
    }

    public function toElasticsearchDocumentArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
        ];
    }

    public function toSearchArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
        ];
    }
    // Ends Methods
}
