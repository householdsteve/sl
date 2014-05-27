<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create('locations', function($table)
    {
        $table->increments('id');
        $table->string('master_id')->unique();
        $table->string('name');
        $table->string('sign');
        $table->string('address');
        $table->string('city');
        $table->string('country_iso');
        $table->string('nation_iso3166');
        $table->string('phone');
        $table->string('email');
        $table->string('phone_mtm_manager');
        $table->string('email_mtm_manager');
        $table->boolean('mtm_store');
        $table->boolean('accepts_gift_card');
        $table->text('hours');
        $table->float('lat');
        $table->float('long');
        $table->string('type');
        $table->string('brand_type');
        $table->text('brands_serialized');
        $table->string('relationship');
        $table->time('date_opened');
        $table->time('date_closed');        
        $table->timestamps();
        $table->softDeletes();
    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		 Schema::drop('locations');
	}

}
