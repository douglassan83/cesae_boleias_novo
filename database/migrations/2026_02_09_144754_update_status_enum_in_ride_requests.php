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
        Schema::table('ride_requests', function (Blueprint $table) {
            //
            DB::statement("
            ALTER TABLE ride_requests
            MODIFY status ENUM('pending', 'accepted', 'rejected', 'cancelled_by_admin')
            NOT NULL ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ride_requests', function (Blueprint $table) {
            //
            DB::statement("
            ALTER TABLE ride_requests
            MODIFY status ENUM('pending', 'accepted', 'rejected')
            NOT NULL ");
        });
    }
};
