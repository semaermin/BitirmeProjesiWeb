<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddUuidToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->after('id')->unique()->nullable(); // UUID sütununu ekliyoruz
        });

        // Mevcut kullanıcılara UUID değerleri eklemek için
        DB::table('users')->get()->each(function ($user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['uuid' => (string) Str::uuid()]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change(); // UUID sütununun nullable olmasını kaldırıyoruz
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
}
