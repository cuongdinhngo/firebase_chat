<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    public $table = "friends";

    protected $fillable = ['requestor_id', 'answer_id', 'status'];
}
