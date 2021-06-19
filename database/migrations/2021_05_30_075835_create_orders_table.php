<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('from_address');
			$table->string('to_address');
			$table->integer('user_id');
			$table->integer('driver_id')->nullable();
			$table->string('client_phone');
			$table->string('size');
			$table->string('client_name');
			$table->string('state');
			$table->string('weight');
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}