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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // UNIQUE
            $table->string('code', 255)->unique();

            // REQUIRED
            $table->unsignedBigInteger('price_sum');
            $table->enum('status', ['pending', 'paid', 'cancel'])->default('pending');
            $table->double('tax');

            // OPTIONAL
            $table->enum('payment_method', ['cash', 'debit', 'qris'])->nullable();

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignId('waiter_id')->constrained('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
