<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->integer('total_quantity');
            $table->float('total_amount');
            $table->float('discount_amount');
            $table->float('service_charge');
            $table->float('paid_amount');
            $table->enum('status', ['Paid', 'Due'])->default('Due');
            $table->enum('payment_type', ['Cash', 'Online'])->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
};
