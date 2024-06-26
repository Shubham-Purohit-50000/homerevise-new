<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questions extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'questions';

    protected $fillable = [
        'question_type',
        'standard_id',
        'subject_id',
        'chapter_id',
        'topic_id',
        'questions',
        'questionsImage',
        'correct_answer',
        'correct_marks',
        'options',
    ];

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
