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
        $table->string('name')->nullable();
        $table->string('sign')->nullable();
        $table->string('address')->nullable();
        $table->string('city')->nullable();
        $table->string('country_iso')->nullable();
        $table->string('nation_iso3166')->nullable();
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->string('phone_mtm_manager')->nullable();
        $table->string('email_mtm_area_manager')->nullable();
        $table->string('email_mtm_store_manager')->nullable();
        $table->boolean('mtm_store')->nullable();
        $table->boolean('accepts_gift_card')->nullable();
        $table->text('hours')->nullable();
        $table->float('lat',10,6)->nullable();
        $table->float('long',10,6)->nullable();
        $table->string('type')->nullable();
        $table->string('brand_type')->nullable();
        $table->text('brands_serialized')->nullable();
        $table->string('relationship')->nullable();
        $table->timestamp('date_opened')->nullable();
        $table->timestamp('date_closed')->nullable();        
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
