<?php

namespace Database\Seeders;

use TestSeeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \app\models\user::factory(10)->create();

        // \app\models\user::factory()->create([
        //     'name' => 'test user',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(UsersTableSeeder::class);
        $this->call(TestSeeder::class);
    }
}
