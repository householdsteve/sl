<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::create('translations', function($table)
    {
        $table->increments('id');
        $table->string('master_id')->unique();
        $table->string('language')->nullable();
        $table->string('key_name_reference')->nullable();
        $table->string('value')->nullable();  
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
		Schema::drop('translations');
	}

}
