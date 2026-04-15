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
        Schema::create('exceptions', function (Blueprint $table) {
        $table->id();
        // El especialista que falta (user_id)
        $table->foreignId('specialist_id')->nullable()->constrained('users')->cascadeOnDelete();

        $table->timestamp('start_datetime');
        $table->timestamp('end_datetime');
        $table->string('reason')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exceptions');
    }
};
