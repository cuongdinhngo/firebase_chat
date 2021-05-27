<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
