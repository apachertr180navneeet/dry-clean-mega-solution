<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $currentTimestamp = Carbon::now();
        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'Admin', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp],
            ['name' => 'Customer', 'created_at' => $currentTimestamp, 'updated_at' => $currentTimestamp]
            // Add more default roles if needed
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
