<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description','user_id'];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
