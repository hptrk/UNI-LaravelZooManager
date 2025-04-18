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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('species');
            $table->boolean('is_predator');
            $table->timestamp('born_at');
            $table->string('image_path')->nullable();
            $table->foreignId('enclosure_id')->nullable()->constrained()->nullOnDelete(); // nullOnDelete to allow for soft deletes
            $table->timestamps();
            $table->softDeletes(); // creates deleted_at column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
