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
        Schema::create('enclosures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('limit');
            $table->time('feeding_at');
            $table->timestamps();
        });

        // connection table for User and Enclosure (many-to-many)
        Schema::create('enclosure_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('enclosure_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // unique key to prevent duplicates
            $table->unique(['user_id', 'enclosure_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enclosure_user');
        Schema::dropIfExists('enclosures');
    }
};
