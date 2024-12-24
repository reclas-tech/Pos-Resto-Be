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
        Schema::create('invoice_tables', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('table_id')->constrained('tables');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_tables');
    }
};
