<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('language', function(Blueprint $table)
		{
			$table->increments('language_id');
			$table->string('name', 32);
			$table->string('code', 5);
			$table->string('locale', 255);
			$table->string('image', 64);
			$table->string('directory', 32);
			$table->integer('sort_order')->default(0);
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
		Schema::drop('language');
	}

}
