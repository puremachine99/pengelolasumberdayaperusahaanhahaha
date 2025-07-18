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
        Schema::create('company_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->string('category')->nullable(); // bisa listrik, sewa, dsb
            $table->string('payment_method')->nullable(); // cash, transfer, etc
            $table->string('receipt')->nullable(); // nama file bukti
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_expenses');
    }
};
