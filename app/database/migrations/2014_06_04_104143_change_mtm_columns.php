<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMtmColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::table('locations', function($table) {
        $table->dropColumn('email_mtm_manager');
        $table->string('email_mtm_area_manager')->nullable();
        $table->string('email_mtm_store_manager')->nullable();
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
        $t->dropColumn('email_mtm_area_manager');
        $t->dropColumn('email_mtm_store_manager');
    });
	}

}
