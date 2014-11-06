<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TranslateCellColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::table('locations', function($table) {
        $table->string('co_sign')->nullable()->after("sign");
        $table->text('column_translations')->nullable()->after("date_closed");
      });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
    Schema::table('locations', function($t) {
        $t->dropColumn('co_sign');
        $t->dropColumn('column_translations');
    });
	}

}
