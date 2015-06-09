<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TranslationKey extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
    Schema::table('locations', function($table) {
        $table->string('sign_translation_key')->nullable()->after("co_sign");
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
        $t->dropColumn('sign_translation_key');
    });
	}

}
