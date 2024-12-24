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
        Schema::create('employee_refresh_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // REQUIRED
            $table->string('token', 255);
            $table->dateTime('expired_at');

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignUuid('employee_id')->constrained('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_refresh_tokens');
    }
};
