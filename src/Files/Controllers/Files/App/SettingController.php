<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller implements  \Illuminate\Routing\Controllers\HasMiddleware {

	public static function middleware() {
		return checkPermissionMiddleWare(PERM_SETTING);
	}



	// --------------------------------------------------------------------------------------------------------------------------
	public function index() {
		// getting all the settings
		$settings = Setting::allSettings();

		$hasRootPermission = \Gate::check('permission', [PERM_ROOT]);

		return generateResponse(RES_SUCCESS, array(RK_SETTINGS => $settings, RK_HAS_ROOT_PERMISSION => $hasRootPermission));

	}


	// ----------------------------------------------------------------------------------------------------------------------------------------------
	public function update(Request $request) {

		$this->updateAppVersionSettings();

		return generateResponse(RES_SUCCESS, [RK_MESSAGE => "تنظیمات با موفقیت ذخیره شد"]);
	}

	private function updateAppVersionSettings() {
		Setting::where(COL_SETTING_NAME, S_MINIMUM_APP_VERSION_ANDROID)->update([COL_SETTING_VALUE => request(S_MINIMUM_APP_VERSION_ANDROID)]);
		Setting::where(COL_SETTING_NAME, S_LATEST_APP_VERSION_ANDROID)->update([COL_SETTING_VALUE => request(S_LATEST_APP_VERSION_ANDROID)]);
	}




	//------------------------------------------------------------------------------------------------------------------------------------
	// this function places (and removes) maintenanceModeEnabled() function (which is inside ApiParentController) inside the ApiParentController construct
	// so it can disable all APIs when app is under maintenance
	public function maintenanceModeOperation() {
		$operation = request('operation');

		list($content, $controllerPath) = $this->readControllerDataAndPath();

		if ($operation == 'activate') {
			$content = $this->activeMaintenanceMode($content);

		} elseif ($operation == 'deactive') {
			$content = $this->deAactiveMaintenanceMode($content);
		}

		file_put_contents($controllerPath, $content);

		return sucBack('ثبت شد');
	}

	private function readControllerDataAndPath() {
		global $BASE_DIR_LARAVEL;
		$controllerPath = $BASE_DIR_LARAVEL . 'app/Http/Controllers/Api/ApiParentController.php';
		$content = file_get_contents($controllerPath);

		return array($content, $controllerPath);
	}


	private function activeMaintenanceMode($content) {

		$constructPos = strpos($content, '//maintenance');
		$maintenanceFunction = 'return $this->maintenanceModeEnabled();';
		$content = substr_replace($content, $maintenanceFunction, $constructPos, 0);

		return $content;
	}


	private function deAactiveMaintenanceMode($content) {
		$content = str_replace('return $this->maintenanceModeEnabled();', '', $content);

		return $content;
	}


}
