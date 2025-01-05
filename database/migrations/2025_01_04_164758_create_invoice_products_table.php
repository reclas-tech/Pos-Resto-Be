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
        Schema::create('invoice_products', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            // REQUIRED
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('price_sum');
            $table->unsignedBigInteger('profit');

            // OPTIONAL
            $table->string('note', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignUuid('invoice_id')->constrained('invoices');
            $table->foreignUuid('product_id')->constrained('products');
            $table->foreignUuid('updated_by')->nullable()->constrained('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_products');
    }
};
