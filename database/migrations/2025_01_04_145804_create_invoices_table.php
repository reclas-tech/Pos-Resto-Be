<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Invoice;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            // UNIQUE
            $table->string('code', 255)->unique();

            // REQUIRED
            $table->enum('type', Invoice::TYPE);
            $table->enum('status', Invoice::STATUS)->default(Invoice::PENDING);
            $table->string('customer', 255);
            $table->double('tax');
            $table->unsignedBigInteger('price_item');
            $table->unsignedBigInteger('price_sum');
            $table->unsignedBigInteger('profit');

            // OPTIONAL
            $table->enum('payment', Invoice::PAYMENT)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignUuid('created_by')->constrained('employees');
            $table->foreignUuid('updated_by')->nullable()->constrained('employees');
            $table->foreignUuid('cashier_id')->nullable()->constrained('employees');
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
