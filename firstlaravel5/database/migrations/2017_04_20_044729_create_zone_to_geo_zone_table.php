<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZoneToGeoZoneTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zone_to_geo_zone', function(Blueprint $table)
		{
			$table->increments('zone_to_geo_zone_id');
			$table->integer('country_id');
			$table->integer('zone_id');
			$table->integer('geo_zone_id');
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
		Schema::drop('zone_to_geo_zone');
	}

}
