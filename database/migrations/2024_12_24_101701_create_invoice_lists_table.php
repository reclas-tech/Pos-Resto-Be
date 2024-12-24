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
        Schema::create('invoice_lists', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // REQUIRED
            $table->unsignedBigInteger('price_sum');
            $table->unsignedInteger('quantity');

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('product_id')->constrained('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_lists');
    }
};
