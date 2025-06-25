<?php

namespace App\Extras;
use App\Exceptions\ErrorMessageException;
use App\Exceptions\ValidationException;
use App\Rules\SimpleString;
use Illuminate\Validation\Rule;


class Validator {

//integers :	['required / nullable'	,	'digits:7 / digits_between:1,5'        	, 'numeric'],
//strings :  	['required / nullable' 	, 	'size:7 / between:1,5  /  min:1,max:5' 	,  new SimpleString(true)],


	public static function requestValidator($validatorRules) {
		$validatorRules=self::appendExtraConditions($validatorRules);
		return self::arrayValidator(request()->all(), $validatorRules);
	}

	private static function appendExtraConditions($validatorRules) {

		$extras=[
			P_LOADED_COUNT => ['nullable','digits_between:1,5', new SimpleString(true)],
			P_OS => ['nullable','between:3,7', new SimpleString(true)],
			P_APP_VERSION => ['nullable','digits_between:1,5'],
			P_LANG => ['nullable','size:2', new SimpleString(true)],
			P_FIREBASE_TOKEN => ['nullable', new SimpleString()],
		];

		$validatorRules=array_merge($extras,$validatorRules);
		return $validatorRules;
	}

	public static function arrayValidator($array,$validatorRules) {
		$validator = \Illuminate\Support\Facades\Validator::make($array, $validatorRules);
		if ($validator->fails()) {
			$message = "لطفا اطلاعات را اصلاح نمایید.";

			foreach ($validator->errors()->all() AS $err) {
				$message .= "\n -$err";
			}
			throw new ValidationException($message);
		} else {
			return true;
		}
	}


	const YYYYMMDD_regex = "regex:/^([0-9]{4})(\/)([1-9]|0[1-9]|1[0-2])(\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/";
	const YYYYMM_regex = "regex:/^([0-9]{4})(\/)([1-9]|0[1-9]|1[0-2])$/";
	const string_regex = "regex:/^[^\"`'#%&;<>{}~\$\*\\\[\]\^]+$/";
	const string_strict_regex = "regex:/^[^\"`'#%&;<>{}~\$\*\/\\\[\]\^!=@:\(\)\?]+$/i";
	const pass_regex = "regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
	const user_pass_regex = "regex:/^[^\"`'%;<>{}~\\[\]\^]+$/";
	const email_regex = "regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
	const phone_number_regex = "regex:/^\+?[0-9][0-9]{7,14}$/";
	const description_regex = "regex:/^[^\"`#%&;<>{}~\$\*\/\\\[\]\^]+$/";


	//---------------------------------------------------------------------------------------------------------------
	//------------------------------------------   General   --------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------

	public static function idValidation($id) {
		$v = [
			'id' => ['nullable', 'min:0', 'numeric'],
		];
		self::arrayValidator(['id'=>$id], $v);
	}

	public static function editingValidation($arrayData) {
		$v = [
			'id' => ['required', 'numeric'],
			'param' => ['required', 'min:2','max:5000', new SimpleString()],
		];
		self::arrayValidator($arrayData, $v);
	}


	//---------------------------------------------------------------------------------------------------------------
	//--------------------------------------------   Admin   --------------------------------------------------------
	//---------------------------------------------------------------------------------------------------------------


	public static function adminDoLoginValidation() {
		$v = [
			'username' => ['required', 'min:3', 'max:50', new SimpleString(true)],
			'password' => ['required', 'min:6', 'max:20', self::pass_regex],
		];
		self::requestValidator($v);
	}

	public static function adminCaptchaValidation() {
		$v = [
			'captcha' => ['required', self::string_regex],
		];
		self::requestValidator($v);
	}

	public static function adminStoreValidation() {
		$v = [
			COL_ADMIN_NAME => ['required', new SimpleString(true)],
			COL_ADMIN_USERNAME => ['required', 'unique:admins', new SimpleString(true)],
			COL_ADMIN_PASSWORD => ['required', 'min:6', 'max:20', self::pass_regex],
		];
		self::requestValidator($v);
	}


	public static function adminUpdateValidator() {
		$v = [
			COL_ADMIN_NAME => ['required', 'max:50', new SimpleString()],
			COL_ADMIN_USERNAME => ['required', new SimpleString(true)],
		];
		self::requestValidator($v);
	}

	public static function adminUploadProfileValidation() {
		$v = [
			'file' => ['required','max:4096' ,'file'],
		];
		self::requestValidator($v);
	}

	public static function adminChangePassValidation() {
		$v = [
			'old_password' => ['required', 'min:6', 'max:20', self::pass_regex],
			'new_password' => ['required', 'min:6', 'max:20', self::pass_regex],
		];
		self::requestValidator($v);
	}


	public static function adminRoleToggleValidation() {
		$v = [
			'admin_id' => ['required', 'numeric'],
			'role_id' => ['required', 'numeric'],
		];
		self::requestValidator($v);
	}

	public static function adminSetNewPasswordeValidation() {
		$v = [
			COL_ADMIN_ID => ['required'],
			COL_ADMIN_PASSWORD => ['required', 'min:6', 'max:20', self::pass_regex],
		];
		self::requestValidator($v);
	}


	public static function rolePermissionToggleValidator() {
		$v = [
			'role_id' => ['required', 'numeric'],
			'permission_id' => ['required', 'numeric'],
		];
		self::requestValidator($v);
	}


	public static function roleStoreValidator() {
		$v = [
			'name' => ['required', 'max:50', new SimpleString()],
			'desc' => ['required', 'max:5000', new SimpleString()],
		];
		self::requestValidator($v);
	}


	public static function roleUpdateValidator() {
		$v = [
			'name' => ['required', 'max:50', new SimpleString()],
			'desc' => ['required', 'max:5000', new SimpleString()],
		];
		self::requestValidator($v);
	}

	public static function adminColumnToggleValidation() {
		$v = [
			'columns' =>'array',
			'type' => ['required', 'min:2', 'max:30', new SimpleString(true)],
		];
		self::requestValidator($v);
	}

	public static function uploadFileGetRequestLogsValidator() {
		$v = [
			'target_id' => ['required', 'integer'],
			'target' => ['required', Rule::in([
				ENUM_UPLOAD_FILE_TARGET_TYPE_USER,
			])],
		];

		self::requestValidator($v);
	}

}
