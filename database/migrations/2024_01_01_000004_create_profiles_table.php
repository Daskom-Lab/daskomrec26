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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('name', 100)->nullable();
            $table->enum('major', ['Teknik Elektro', 'Teknik Biomedis', 'Teknik Fisika', 'Teknik Telekomunikasi', 'Teknik Sistem Energi'])->nullable();
            $table->string('class', 50)->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
