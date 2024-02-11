<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['user_id', 'level_number', 'required_points'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
