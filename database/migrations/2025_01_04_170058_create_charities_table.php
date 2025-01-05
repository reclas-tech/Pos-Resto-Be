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
        Schema::create('charities', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            // REQUIRED
            $table->double('value');
            $table->char('year', 4);
            $table->unsignedSmallInteger('month');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charities');
    }
};
