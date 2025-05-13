<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_name'); // Nama produk
            $table->decimal('price', 10, 2); // Harga satuan
            $table->integer('quantity'); // Jumlah barang yang dibeli
            $table->decimal('total_price', 10, 2); // Harga total (price * quantity)
            $table->string('status')->default('pending'); // Status barang
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
