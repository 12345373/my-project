<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    use HasFactory;

    protected $table = 'grade_levels';

    protected $fillable = [
        'name',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'grade_level');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'grade_level_id');
    }
}