<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('discounts', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique();
        $table->enum('type', ['percentage', 'nominal']);
        $table->decimal('value', 10, 2);
        $table->decimal('min_purchase', 10, 2)->default(0); // Minimal pembelian
        $table->date('expiration_date')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
