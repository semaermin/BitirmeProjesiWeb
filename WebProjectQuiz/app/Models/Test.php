<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Test extends Model
{
    protected $fillable = ['name', 'admin_id', 'slug','learning_purpose', 'duration_minutes'];

    // protected $hidden= [
    //     'id',
    //     'admin_id',
    // ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($test) {
            $test->slug = Str::slug($test->name);
        });
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }
}


