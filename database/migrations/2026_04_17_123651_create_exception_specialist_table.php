<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exception_specialist', function (Blueprint $table) {
            $table->foreignId('exception_id')->constrained('exceptions')->cascadeOnDelete();
            $table->foreignId('specialist_user_id')->constrained('users')->cascadeOnDelete();

            $table->primary(['exception_id', 'specialist_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exception_specialist');
    }
};
