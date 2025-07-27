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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['percent', 'fixed', 'b1g1', 'cashback', 'bonus']);
            $table->decimal('value', 10, 2)->nullable(); // diskon/cashback
            $table->json('conditions')->nullable();     // untuk menu_id, qty, dll
            $table->dateTime('active_from')->nullable();
            $table->dateTime('active_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
