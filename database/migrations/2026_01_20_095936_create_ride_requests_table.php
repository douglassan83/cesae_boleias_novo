<?php
// ========================================
// MIGRATION RIDE REQUESTS CESAE BOLEIAS
// Pedidos passageiro → motorista aprova
// ========================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ride_requests', function (Blueprint $table) {
            $table->id();  // ID auto

            // BOLEIA pedida (FK rides)
            $table->foreignId('ride_id')->constrained()->onDelete('cascade');

            // PASSAGEIRO que pediu (FK users - user-passageiro logado)
            $table->foreignId('passenger_id')->constrained('users')->onDelete('cascade');

            // DADOS EXTRA do passageiro (form pede)
            $table->string('passenger_name')->nullable();     // Nome completo
            $table->string('phone')->nullable();              // Contato WHATS
            $table->string('pickup_point')->nullable();       // "Rua X esquina Y"
            $table->text('message')->nullable();              // "Chego 8h certinho"

            // MOTORISTA decide: pending → accepted/rejected
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ride_requests');
    }
};
