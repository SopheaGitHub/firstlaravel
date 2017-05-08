<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('post', function(Blueprint $table)
		{
			$table->increments('post_id');
			$table->integer('post_type_id');
			$table->integer('author_id');
			$table->integer('updated_by_author_id');
			$table->string('image', 255);
			$table->integer('sort_order')->default(0);
			$table->integer('status')->default(0);
			$table->integer('viewed')->default(0);
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
		Schema::drop('post');
	}

}
