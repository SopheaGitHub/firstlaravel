<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLayoutRouteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('layout_route', function(Blueprint $table)
		{
			$table->increments('layout_route_id');
			$table->integer('layout_id');
			$table->integer('website_id');
			$table->string('route', 255);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('layout_route');
	}

}
