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
        Schema::table('initiatives', function(Blueprint $table){
            $table->string('photo');
            $table->date('endDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('initiatives', function(Blueprint $table){
            $table->dropColumn('photo');
            $table->dropColumn('endDate');
        });
    }
};
