<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id')->unsigned();
            $table->string('currency', 4);
            $table->string('bic', 11);
            $table->integer('serial_number', 18)->unsigned();
            $table->date('date');
            $table->date('value_date');
            $table->float('amount', 18, 2);
            $table->float('balance_after_transaction', 18, 2)->nullable();
            $table->string('counterparty_iban', 34);
            $table->string('counterparty_name', 70);
            $table->string('opposing_party', 70);
            $table->string('initiating_party', 70);
            $table->string('counterparty_bic', 15);
            $table->string('code', 4);
            $table->string('batch_id', 35);
            $table->string('reference', 35);
            $table->string('mandate_code', 35);
            $table->string('creditor_id', 35);
            $table->string('payment_code', 35);
            $table->string('description_1', 140);
            $table->string('description_2', 140);
            $table->string('description_3', 140);
            $table->string('reason_return', 75);
            $table->float('original_amount', 18, 2)->nullable();
            $table->string('original_currency', 11);
            $table->float('exchange_rate', 11, 4)->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')
                  ->onUpdate('cascade')
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
        Schema::dropIfExists('transactions');
    }
}
