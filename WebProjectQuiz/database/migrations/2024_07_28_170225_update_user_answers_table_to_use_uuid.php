<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->uuid('user_uuid')->nullable()->after('user_id');

            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('user_answers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('user_uuid');

            $table->foreign('user_id')->references('id')->on('users');

            $table->dropColumn('user_uuid');
        });
    }
};
