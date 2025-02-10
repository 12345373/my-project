<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $table = 'friends';

    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
        'requested_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(Student::class, 'friend_id');
    }
}