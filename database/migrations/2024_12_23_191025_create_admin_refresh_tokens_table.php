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
        Schema::create('admin_refresh_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // REQUIRED
            $table->string('token', 255);
            $table->dateTime('expired_at');

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignUuid('admin_id')->constrained('admins');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_refresh_tokens');
    }
};
