<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'google_id' => null,
            'is_admin' => false,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Parolayı hashlemek önemlidir
            'level' => 1,
            'rank' => 0,
            'profile_photo_path' => null,
            'remember_token' => Str::random(10),
        ]);

        // Başka kullanıcılar eklemek isterseniz buraya ekleyebilirsiniz
        User::create([
            'google_id' => 'some-google-id',
            'is_admin' => true,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('adminpassword'),
            'level' => 10,
            'rank' => 1,
            'profile_photo_path' => '/path/to/photo.jpg',
            'remember_token' => Str::random(10),
        ]);
    }
}
