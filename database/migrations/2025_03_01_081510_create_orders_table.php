<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('order_id');
            $table->string('name'); // Nama penerima
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('province'); // Provinsi tujuan
            $table->string('city'); // Kota tujuan
            $table->string('courier'); // Jasa pengiriman
            $table->string('service'); // Jenis layanan pengiriman
            $table->decimal('total_cost', 10, 2); // Total harga semua item termasuk ongkir
            $table->string('payment_status')->default('pending'); // Status pembayaran
            $table->string('payment_type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
