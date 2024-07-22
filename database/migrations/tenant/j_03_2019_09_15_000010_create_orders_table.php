<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('invoice_number');
            $table->date('order_date');
            $table->time('order_time');
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->enum('status', ['delivered', 'pending'])->default('pending');
            $table->integer('total_qty')->default(0);
            $table->float('total_price')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('set null');
            $table->foreign('service_id')->references('id')->on('operations')->onDelete('set null');
            // $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
            // $table->foreign('service_id')->references('id')->on('operations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
