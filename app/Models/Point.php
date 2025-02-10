<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $table = 'points';

    protected $fillable = [
        'user_id',
        'exam_id',
        'points_earned',
        'awarded_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}