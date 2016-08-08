<?php

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
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('description');	//Short description for display
            $table->decimal('amount', 5, 2);
            $table->dateTime('datedon');	//Date the expense is to be dated on
            $table->enum('type', ['expense', 'payment', 'withdrawal', 'dividend', 'director_expense' ])->default('payment');
            $table->enum('status', ['potential', 'invoiced', 'paid', 'cancelled' ])->default('payment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
