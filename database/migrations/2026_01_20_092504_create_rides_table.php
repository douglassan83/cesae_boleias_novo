<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();  // ID auto

            // MOTORISTA (FK users)
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');

            // ROTA (nullable + defaults seguros)
            $table->string('pickup_location')->nullable();
            $table->string('destination_location')->nullable();

            // DATA/HORA (nullable seguros)
            $table->date('departure_date')->nullable();
            $table->time('departure_time')->nullable();

            // LUGARES (defaults 4)
            $table->tinyInteger('total_seats')->default(4);
            $table->tinyInteger('available_seats')->default(4);

            // OPCIONAL
            $table->text('observations')->nullable();

            // STATUS (active = disponível)
            $table->enum('status', ['active', 'full', 'cancelled'])->default('active');

            $table->timestamps();  // created_at/updated_at

            // ÍNDICE busca rápida (origem + data)
            $table->index(['pickup_location', 'departure_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
