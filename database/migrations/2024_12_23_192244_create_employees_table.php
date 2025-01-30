<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Employee;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            // UNIQUE
            $table->char('pin', 6)->unique()->nullable();

            // REQUIRED
            $table->string('name', 255);
            $table->string('phone', 15);
            $table->text('address');
            $table->enum('role', Employee::ROLE);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};