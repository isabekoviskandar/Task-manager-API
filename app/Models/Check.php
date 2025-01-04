<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    protected $fillable = ['user_id', 'value'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
