<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('cities', function(Blueprint $table){
            $table->longText('population')->nullable()->change();
            $table->longText('restaurantFoodStablishments')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->longText('designationyear')->nullable()->change();
            $table->string('photo')->nullable()->change();
            $table->string('logo')->nullable()->change();
            $table->string('banner')->nullable()->change();
            $table->longText('completeInfo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function(Blueprint $table){
            $table->longText('population')->nullable(false)->change();
            $table->longText('restaurantFoodStablishments')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->longText('designationyear')->nullable(false)->change();
            $table->string('photo')->nullable(false)->change();
            $table->string('logo')->nullable(false)->change();
            $table->string('banner')->nullable(false)->change();
            $table->longText('completeInfo')->nullable(false)->change();
        });
    }
};
