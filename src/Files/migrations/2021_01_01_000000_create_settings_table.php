<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create(TBL_SETTINGS, function (Blueprint $table) {
			$table->increments(COL_SETTING_ID);
			$table->string(COL_SETTING_NAME, 150);
			$table->longText(COL_SETTING_VALUE);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists(TBL_SETTINGS);
	}
}
