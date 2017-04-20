<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('currency', function(Blueprint $table)
		{
			$table->increments('currency_id');
			$table->string('title', 32);
			$table->string('code', 3);
			$table->string('symbol_left', 12);
			$table->string('symbol_right', 12);
			$table->char('decimal_place', 1);
			$table->float('value', 15, 8);
			$table->integer('status')->default(0);
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
		Schema::drop('currency');
	}

}
