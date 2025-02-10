<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'exam_id',
        'text',
        'options',
        'correct_answer',
        'marks',
        'grade_level_id',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}