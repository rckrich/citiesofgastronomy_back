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
        Schema::table('timeline', function(Blueprint $table){
            $table->text('link')->nullable()->change();
            $table->text('endDate')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timeline', function(Blueprint $table){
            $table->text('link')->nullable(false)->change();
            $table->text('endDate')->nullable(false)->change();
        });
    }
};
