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
        Schema::create('packet_products', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            // REQUIRED
            $table->unsignedInteger('quantity');

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignUuid('product_id')->constrained('products');
            $table->foreignUuid('packet_id')->constrained('packets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packet_products');
    }
};
