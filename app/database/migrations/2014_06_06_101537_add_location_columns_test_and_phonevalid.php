<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationColumnsTestAndPhonevalid extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::table('locations', function($table) {
        $table->string('phone_verified')->nullable();
        $table->text('last_import_data')->nullable();
      });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
    Schema::table('authors', function($t) {
        $t->dropColumn('phone_verified');
        $t->dropColumn('last_import_data');
    });
	}

}
