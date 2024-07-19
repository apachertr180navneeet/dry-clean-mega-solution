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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('amount');
            $table->timestamps();
        });

        $currentTimestamp = Carbon::now();
        // Insert default roles
        DB::table('discounts')->insert([
            ['name' => '5% Discount','amount'=> '5', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['name' => '10% Discount','amount'=> '10', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['name' => '15% Discount', 'amount'=> '15', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['name' => '20% Discount','amount'=> '20', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp]
            // Add more default roles if needed
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
};
