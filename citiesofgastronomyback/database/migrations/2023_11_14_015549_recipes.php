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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('idChef');
            $table->integer('idCity');
            $table->integer('idCategory');
            $table->string('name');
            $table->string('photo');
            $table->text('description');
            $table->text('difficulty');
            $table->text('prepTime');
            $table->text('totalTime');
            $table->text('servings');
            $table->text('ingredients');
            $table->text('preparations');

            $table->tinyInteger('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
