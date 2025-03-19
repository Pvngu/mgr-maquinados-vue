<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('creditors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('first_name')->nullable()->default(null);
            $table->string('last_name')->nullable()->default(null);
            $table->string('email')->nullable()->default(null);
            $table->string('phone_number')->nullable()->default(null);
            $table->string('address_line1')->nullable()->default(null);
            $table->enum('type', ['individual', 'business'])->nullable()->default(null);
            $table->string('city')->nullable()->default(value: null);
            $table->bigInteger('state_id')->unsigned()->nullable()->default(null);
            $table->foreign('state_id')->references('id')->on('states')->onUpdate('cascade');
            $table->string('zip_code')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creditors');
    }
};
