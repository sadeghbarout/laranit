<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create(TBL_ADMINS, function (Blueprint $table) {
			$table->increments(COL_ADMIN_ID);
			$table->string(COL_ADMIN_NAME)->default('');
			$table->string(COL_ADMIN_USERNAME)->unique();
			$table->string(COL_ADMIN_PASSWORD)->default('');
			$table->string(COL_ADMIN_IMAGE, 100)->default('');
			$table->rememberToken();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists(TBL_ADMINS);
	}
};
