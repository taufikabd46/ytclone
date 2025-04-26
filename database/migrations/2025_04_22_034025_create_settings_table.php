<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            //randomize, 1 or 0
            $table->boolean('randomize')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Seed the settings table with default values.
     */
    public function seedSettings(): void
    {
        DB::table('settings')->insert([
            'randomize' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
