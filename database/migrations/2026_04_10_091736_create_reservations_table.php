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
        Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cite_id')->constrained('cites')->cascadeOnDelete();
        // El paciente es un user_id (de la tabla users)
        $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();

        $table->string('status')->default('confirmed'); // confirmed, cancelled
        $table->string('payment_status')->default('pending'); // pending, paid

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
