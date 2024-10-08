<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('admin_id')->constrained('users');
            $table->enum('learning_purpose', ['is', 'egitim', 'seyehat', 'eglence', 'kultur', 'ailevearkadaslar'])->default('egitim');//a1-a2-b1-b2 olarak değiştirilebilir
            // $table->dateTime('start_date');
            // $table->dateTime('end_date');
            $table->unsignedInteger('duration_minutes');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
