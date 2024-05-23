<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['test_id', 'text', 'type'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
    public function matchingOptions()
    {
        return $this->hasMany(MatchingOption::class);
    }
    // public function video()
    // {
    //     return $this->hasOne(Video::class);
    // }
}
