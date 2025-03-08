<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'grade_level');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'user_id');
    }

    public function friends()
    {
        return $this->hasMany(Friend::class, 'user_id');
    }
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function sentFriendshipRequests()
    {
        return $this->hasMany(FriendshipRequest::class, 'sender_id');
    }

    public function receivedFriendshipRequests()
    {
        return $this->hasMany(FriendshipRequest::class, 'receiver_id');
    }
    }
