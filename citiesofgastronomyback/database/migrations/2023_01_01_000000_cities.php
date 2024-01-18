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
        Schema::create('cities', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name');
            $table->string('country');
            $table->foreignId('idContinent')->constrained('continent')->onDelete('cascade');
            $table->longText('population');
            $table->longText('restaurantFoodStablishments');
            $table->text('description');
            $table->longText('designationyear');
            $table->string('photo');
            $table->string('logo');
            $table->string('banner');
            $table->longText('completeInfo');

            $table->tinyInteger('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
