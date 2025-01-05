<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cash_on_hands', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            // REQUIRED
            $table->unsignedBigInteger('cash');

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignUuid('cashier_id')->constrained('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_on_hands');
    }
};
