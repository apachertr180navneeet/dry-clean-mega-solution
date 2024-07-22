<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Get current timestamp
        $currentTimestamp = Carbon::now();

        DB::table('products')->insert([
            ['name' => 'Clothes', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['name' => 'Upholstery', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['name' => 'Footwear & Bags', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['name' => 'Others', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['name' => 'Laundry', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            // Add more default roles if needed
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
