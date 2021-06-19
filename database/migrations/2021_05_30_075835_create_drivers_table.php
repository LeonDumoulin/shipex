<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDriversTable extends Migration {

	public function up()
	{
		Schema::create('drivers', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('username');
			$table->string('password');
			$table->string('phone');
            $table->string('image')->nullable();
			$table->string('car');
			$table->string('name');
			$table->string('user_rate')->nullable();
			$table->string('count_rate')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('drivers');
	}
}