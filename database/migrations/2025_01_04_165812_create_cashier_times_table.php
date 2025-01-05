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
        Schema::create('cashier_times', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            // REQUIRED
            $table->dateTime('started_at');

            // OPTIONAL
            $table->dateTime('ended_at')->nullable();

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
        Schema::dropIfExists('cashier_times');
    }
};
