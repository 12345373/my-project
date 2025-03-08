<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendshipRequest extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'status'];

    public function sender()
    {
        return $this->belongsTo(Student::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Student::class, 'receiver_id');
    }
} // هنا لا توجد فاصلة منقوطة

