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
        Schema::create('packets', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            // UNIQUE
            $table->string('name', 255)->unique();

            // REQUIRED
            $table->unsignedBigInteger('cogp');
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('stock');
            $table->text('image');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packets');
    }
};
