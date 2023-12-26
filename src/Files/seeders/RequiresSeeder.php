<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RequiresSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		\App\Models\App\Setting::create([COL_SETTING_NAME => S_MINIMUM_APP_VERSION_ANDROID, COL_SETTING_VALUE => '0']);
		\App\Models\App\Setting::create([COL_SETTING_NAME => S_LATEST_APP_VERSION_ANDROID, COL_SETTING_VALUE => '0']);
		\App\Models\App\Setting::create([COL_SETTING_NAME => S_SETTINGS_VERSION, COL_SETTING_VALUE => '1']);


		\App\Models\User\Admin::create([COL_ADMIN_USERNAME => "admin", COL_ADMIN_PASSWORD=> bcrypt('123456aA*'),COL_ADMIN_NAME=>'مدیر ارشد سیستم'])->roles()->sync([1]);

	}

}
