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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained('tests');
            // $table->boolval('is_video');
            $table->text('text');
            $table->string('type');
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');//a1-a2-b1-b2 olarak değiştirilebilir
            $table->unsignedInteger('points')->default(2);
            $table->string('media_path')->nullable(); // Medya dosyasının yolu veya adı
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }

};
