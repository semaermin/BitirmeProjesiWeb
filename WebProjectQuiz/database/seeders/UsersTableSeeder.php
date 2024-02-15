<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Örnek kullanıcılar oluştur
        User::create([
            'name' => 'sema',
            'email' => 'sema@gmail.com',
            'password' => bcrypt('password'),
        ]);


    }
}
