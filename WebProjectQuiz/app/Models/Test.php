<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Test extends Model
{
    protected $fillable = ['name', 'admin_id', 'slug', 'start_date', 'end_date','duration_minutes'];

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


