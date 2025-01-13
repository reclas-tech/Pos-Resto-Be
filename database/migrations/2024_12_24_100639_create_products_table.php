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
        Schema::create('products', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->string('name', 255);

            // REQUIRED
            $table->unsignedBigInteger('cogp');
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('stock');
            $table->text('image');

            $table->timestamps();
            $table->softDeletes();

            // FOREIGN KEY
            $table->foreignUuid('category_id')->constrained('categories');
            $table->foreignUuid('kitchen_id')->constrained('kitchens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
