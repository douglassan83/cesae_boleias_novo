<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabela para pedidos de reversão de recusa.
     * Passageiro solicita → Admin aprova → Pedido volta a "pending".
     */
    public function up(): void
    {
        Schema::create('ride_reversal_requests', function (Blueprint $table) {
            $table->id();

            // ID do pedido original recusado
            $table->unsignedBigInteger('ride_request_id');

            // Passageiro que solicitou a reversão
            $table->unsignedBigInteger('passenger_id');

            // Estado do pedido: pending, approved, rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending');

            // Notas do admin (opcional)
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            // FK
            $table->foreign('ride_request_id')->references('id')->on('ride_requests')->onDelete('cascade');
            $table->foreign('passenger_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_reversal_requests');
    }
};
