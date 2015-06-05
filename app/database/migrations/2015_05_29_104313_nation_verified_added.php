<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NationVerifiedAdded extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::table('locations', function($table) {
        $table->string('nation_verified')->nullable()->after("nation_iso3166");
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
        $t->dropColumn('nation_verified');
    });
	}

}
