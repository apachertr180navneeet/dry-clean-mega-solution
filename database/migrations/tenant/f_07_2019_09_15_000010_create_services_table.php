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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $currentTimestamp = Carbon::now();
        DB::table('services')->insert([
            ['name'=>'basic', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['name'=>'express', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
