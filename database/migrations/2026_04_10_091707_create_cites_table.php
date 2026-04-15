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
        Schema::create('cites', function (Blueprint $table) {
        $table->id();
        $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
        $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
        // Añadimos la relación con la regla para saber de dónde viene el hueco
        $table->foreignId('schedule_rule_id')->nullable()->constrained('schedule_rules')->nullOnDelete();

        $table->date('date');
        $table->time('start_time');
        $table->time('end_time');

        // available, cancelled, completed
        $table->string('status')->default('available');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cites');
    }
};
