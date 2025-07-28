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
        // database/migrations/xxxx_xx_xx_create_cash_transactions_table.php
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['in', 'out']); // kas masuk / kas keluar
            $table->decimal('amount', 12, 2);
            $table->string('category')->nullable();
            $table->string('payment_method')->nullable();
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->string('source')->nullable(); // optional: dari salaries / claims / manual
            $table->unsignedBigInteger('source_id')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
