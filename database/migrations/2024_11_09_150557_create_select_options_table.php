<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('select_options', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->string('key');
            $table->string('value');
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'SelectOptionSeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('select_options');
    }
};
