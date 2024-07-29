<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'point', 'level', 'profile_photo_path'
    ];

    protected $attributes = [
        'is_admin' => 1,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'is_admin',
        'google_id',
        'two_factor_confirmed_at',
        'email_verified_at',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }

    public function points()
    {
        return $this->hasMany(Point::class);
    }

    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    // Kullanıcının oluşturduğu testler
    public function tests()
    {
        return $this->hasMany(Test::class, 'admin_id');
    }

    public function isAdmin()
    {
        return $this->is_admin === 1;
    }



}
