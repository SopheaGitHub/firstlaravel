<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLayoutModuleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('layout_module', function(Blueprint $table)
		{
			$table->increments('layout_module_id');
			$table->integer('layout_id');
			$table->string('code', 64);
			$table->string('position', 14);
			$table->integer('sort_order')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('layout_module');
	}

}
