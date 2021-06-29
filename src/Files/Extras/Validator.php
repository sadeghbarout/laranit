<?php

namespace App\Extras;
use App\Exceptions\ErrorMessageException;
use App\Exceptions\ValidationException;

class Validator {


    public static function requestValidator($validatorRules) {
        return self::arrayValidator(request()->all(),$validatorRules);
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


    const YYYYMMDD_regex="regex:/^([0-9]{4})(\/)([1-9]|0[1-9]|1[0-2])(\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/";
    const YYYYMM_regex="regex:/^([0-9]{4})(\/)([1-9]|0[1-9]|1[0-2])$/";
    //---------------------------------------------------------------------------------------------------------------

    public static function editingValidation($arrayData) {
        $v = [
            'id' => 'required|numeric',
            'param' => 'required|min:2',
        ];
        self::arrayValidator($arrayData,$v);
    }

	public static function adminDoLoginValidation() {
		$v = [
			'username' => 'required|min:3|max:50',
			'password' => 'required|min:6|max:20',
		];
		self::requestValidator($v);
	}

	public static function adminStoreValidation() {
		$v = [
			COL_ADMIN_NAME => 'required',
			COL_ADMIN_USERNAME => 'required|unique:admins',
			COL_ADMIN_PASSWORD => 'required|min:6|max:20|confirmed',
		];
		self::requestValidator($v);
	}


	public static function adminUpdateValidator() {
		$v = [
			COL_ADMIN_NAME => 'required',
			COL_ADMIN_USERNAME => 'required',
		];
		self::requestValidator($v);
	}

	public static function adminUploadProfileValidation() {
		$v=[
			'file'=>'required|file',
		];
		self::requestValidator($v);
	}

	public static function adminChangePassValidation() {
		$v = [
			'old_password' => 'required|min:6|max:20',
			'new_password' => 'required|min:6|max:20',
		];
		self::requestValidator($v);
	}


}
