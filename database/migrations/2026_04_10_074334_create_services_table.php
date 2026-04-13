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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('duration'); // minutes
            $table->integer('buffer_minutes');
            $table->decimal('price', 6, 2); // 6 dígitos en total, 2 decimales, creo que será suficiente
            $table->string('type'); // individual, group
            $table->integer('max_patients');
            $table->boolean('active');
            $table->timestamps(); // Esto genera created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
