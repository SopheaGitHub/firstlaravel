<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerImageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('banner_image', function(Blueprint $table)
		{
			$table->increments('banner_image_id');
			$table->integer('banner_id');
			$table->integer('language_id');
			$table->string('title', 64);
			$table->string('link', 255);
			$table->string('image', 255);
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
		Schema::drop('banner_image');
	}

}
