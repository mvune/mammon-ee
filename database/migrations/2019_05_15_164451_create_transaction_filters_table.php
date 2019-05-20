<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('target_id')->unsigned();
            $table->string('expression');
            $table->integer('category_id')->unsigned();
            $table->timestamps();

            $table->foreign('target_id')->references('id')->on('transaction_filter_targets');
            $table->foreign('category_id')->references('id')->on('categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_filters');
    }
}
