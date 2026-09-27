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
			$table->text(COL_ADMIN_SELECTED_COLUMNS)->nullable();

			$table->ipAddress(COL_ADMIN_IP)->default('')->index();
			$table->dateTime(COL_ADMIN_LAST_LOGIN)->nullable();
			$table->string(COL_ADMIN_DEVICE_INFO,250)->default('');

			$table->enum(COL_ADMIN_STATUS,[ENUM_ADMIN_STATUS_ACTIVE, ENUM_ADMIN_STATUS_INACTIVE])->default(ENUM_ADMIN_STATUS_ACTIVE);

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
