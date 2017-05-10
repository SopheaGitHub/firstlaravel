<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerImageDescriptionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('banner_image_description', function(Blueprint $table)
		{
			$table->integer('banner_image_id');
			$table->integer('language_id');
			$table->integer('banner_id');
			$table->string('title', 64);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('banner_image_description');
	}

}
