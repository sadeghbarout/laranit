<?php

namespace App\Http\Controllers\Api;

use App\Extras\StatusCodes;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class AppApiController extends ApiParentController {


	public function appStart() {
		$os = strtolower(request(P_OS, 'Android'));
		$appVersion = request(P_APP_VERSION, 0);
		$firebaseToken = request(P_FIREBASE_TOKEN, 0);
		$settingsVersion = request(P_SETTINGS_VERSION, 0);

		$settings = Setting::allSettings();

		$settingList = $this->checkUserAppVersionBasedOnOS($os, $appVersion, $settings);

		$settingList = $this->getCustomerSettingList($settings, $settingsVersion, $os, $firebaseToken, $settingList);

		return generateResponse(RES_SUCCESS, [RK_SETTINGS => (object)$settingList]);
	}


	private function checkUserAppVersionBasedOnOS($os, $appVersion, $settings) {
		$settingList = array();
		// check if os is android or ios or pwa and return the correct application download link if customer app version < min version && latest version
//		if ($os=="PWA") {
//			if ($settings[S_MINIMUM_APP_VERSION_PWA] > $appVersion)
//				return response(generateResponse(ERR_MINIMUM_APP_VERSION, [RK_UPDATE_URL=> "https://app.".SITE_URL . "/"]), StatusCodes::HTTP_FOUND);
//
//			if ($settings[S_LATEST_APP_VERSION_PWA] > $appVersion)
//				$settingList[RK_UPDATE_URL] = "https://app.".SITE_URL . "/";
//
//		} elseif ($os=="iOS") {
//			if ($settings[S_MINIMUM_APP_VERSION_IOS] > $appVersion)
//				return response(generateResponse(ERR_MINIMUM_APP_VERSION, [RK_UPDATE_URL=> "https://".SITE_URL_API . "/app"]), StatusCodes::HTTP_FOUND);
//
//			if ($settings[S_LATEST_APP_VERSION_IOS] > $appVersion)
//				$settingList[RK_UPDATE_URL] = "https://".SITE_URL_API . "/app";
//
//		} else {
		if ($settings[S_MINIMUM_APP_VERSION_ANDROID] > $appVersion)
			return response(generateResponse(ERR_MINIMUM_APP_VERSION, [RK_UPDATE_URL => "https://" . SITE_URL]), StatusCodes::HTTP_FOUND);

		if ($settings[S_LATEST_APP_VERSION_ANDROID] > $appVersion)
			$settingList[RK_UPDATE_URL] = "https://" . SITE_URL;
//        }

		return $settingList;
	}


	private function getCustomerSettingList($settings, $settingsVersion, $os, $firebaseToken, $settingList) {
		$customer = self::authorizeToken([COL_USER_ID, COL_USER_EMAIL, COL_USER_CODE, COL_USER_PHONENUMBER, COL_USER_FIRST_NAME, COL_USER_LAST_ACTIVITY, COL_USER_FIREBASE_TOKEN, COL_USER_PROFILE_IMAGE], true, false);
		if ($customer) {

			$settingList = $this->getSettingsIfSettingsChanged($settings, $settingsVersion, $settingList);
			$settingList = $this->getCustomerData($customer, $settingList);
			$this->storeCustomerDeviceInfo($firebaseToken, $os, $customer);

		}
		return $settingList;
	}


	private function getSettingsIfSettingsChanged($settings, $settingsVersion, $settingList) {
		if ($settings[S_SETTINGS_VERSION] > $settingsVersion) {

			// todo:  set settings ...

			$settingList[S_SETTINGS_VERSION] = $settings[S_SETTINGS_VERSION];
		}
		return $settingList;
	}


	private function getCustomerData($customer, $settingList) {
		$customerData = $customer->only([COL_USER_ID, COL_USER_EMAIL, COL_USER_CODE, COL_USER_PHONENUMBER, COL_USER_FIRST_NAME, COL_USER_PROFILE_IMAGE]);
		$settingList[RK_USER] = $customerData;
		return $settingList;
	}


	private function storeCustomerDeviceInfo($firebaseToken, $os, $customer) {
		$customer[COL_USER_LAST_ACTIVITY] = getServerDateTime();
		$customer[COL_USER_IP] = getUserIp();
		$customer[COL_USER_OS] = $os;

		if ($firebaseToken && $firebaseToken != $customer[COL_USER_FIREBASE_TOKEN]) {
			DB::table(TBL_USERS)->where(COL_USER_FIREBASE_TOKEN, $firebaseToken)->update(array(COL_USER_FIREBASE_TOKEN => ''));
			$customer[COL_USER_FIREBASE_TOKEN] = $firebaseToken;
		}
		$customer->save();
	}


	//---------------------------------------------------------------------------------------------------------------------------
	// application main page
	public function mainPage() {
		$customerId = self::authorizeToken();

		return generateResponse(RES_SUCCESS, []);
	}

	//---------------------------------------------------------------------------------------------------------------------------

}
