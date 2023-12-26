<?php

namespace App\Models\User;

use App\Exceptions\ErrorMessageException;
use App\Extras\StatusCodes;
use App\Extras\Tools;
use App\Models\Finance\WalletTransaction;
use App\Models\ModelEnhanced;
use App\Models\App\Country;
use App\Models\App\Notification;
use App\Models\App\SecurityQuestion;
use App\Models\Finance\Payment;
use App\Models\Service\CarOrder;
use App\Models\Service\Consultation;
use App\Models\Service\ProductOrder;
use App\Models\Service\Translation;
use App\Models\Support\Chat;
use App\Models\Transaction\Card;
use App\Models\Transaction\Exchanger;
use App\Models\Transaction\HighTransaction;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
//
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;


/**
 * App\User
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User withCountry($columns)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User withExchangerPlan($columns)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User code($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User phonenumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User phoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User transactionPin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User firstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User lastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User email($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User countryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User status($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereInId($ids)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User isSeen($val)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User firebaseToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User os($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User inviterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User type($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User justBranches()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User justInstants()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User signupStep()
 */
class User extends ModelEnhanced implements AuthenticatableContract,AuthorizableContract,CanResetPasswordContract {
	use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
    use HasFactory;

    public $fillable = [COL_USER_IS_SEEN];
	protected $hidden = [
		COL_ADMIN_PASSWORD,
    ];



	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------   relations   ---------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------



	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeId($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_ID, $value);
		}
		return $query;
	}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeCode($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_CODE, $value);
		}
		return $query;
	}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopePhonenumber($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_PHONENUMBER,'LIKE', "%$value%");
		}
		return $query;
	}




	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeFirstName($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_FIRST_NAME, "LIKE", "%$value%");
		}
		return $query;
	}




	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeEmail($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_EMAIL, $value);
		}
		return $query;
	}




	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeStatus($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_STATUS, $value);
		}
		return $query;
    }




    /* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeWhereInId($query, $ids) {
        if (ModelEnhanced::checkParameter($ids)) {
            return $query->whereIn(TBL_USERS . '.' . COL_USER_ID, $ids);
        }
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeIsSeen($query, $val) {
        if (ModelEnhanced::checkParameter($val)) {
            return $query->where(TBL_USERS . '.' . COL_USER_IS_SEEN, $val);
        }
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeFirebaseToken($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_USERS . '.' . COL_USER_FIREBASE_TOKEN, $value);
        }
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeOs($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_USERS . '.' . COL_USER_OS, $value);
        }
	}



	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------    functions    -------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

    // gets jwt token from request header to authenticate the user
    // its for APIs
	/**
	 * @param       $jwtAPIToken
	 * @param array $cols
	 *
	 * @return User|int|null
	 */
	public static function validateApiToken($jwtAPIToken, $cols = []) {

		$infos = Tools::JWTDecode($jwtAPIToken);

		if ($infos) {
			if ($cols == []) {
				return $infos['id'];

			}
			$cols[] = COL_USER_ID;
			$user = User::id($infos['id'])->first($cols);
			if ($user) {
				return $user;
			} else {
				return null;
			}
		}
		return null;
    }





    //-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------    accessors & mutator    ---------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	public function getProfileImageAttribute($value) {
		return $this->correctImage($value, PATH_PROFILE_IMAGES);
    }


    // decrypting user wallet to get a human readable number
	public function getWalletAttribute($value) {
        return Tools::decryptData($value);
	}


	public function getStatusAttribute($value){
        $this -> append(COL_USER_STATUS.'_text');
        $this -> append(COL_USER_STATUS.'_color');
        return $value;
    }
    public function getStatusTextAttribute(){return UC($this->attributes[COL_USER_STATUS], U_USER_STATUSES);}
    public function getStatusColorAttribute(){return UC($this->attributes[COL_USER_STATUS], U_USER_STATUS_COLOR);}



    public function getCreatedAtAttribute($value){
        $this -> append(COL_USER_CREATED_AT.'_fa');
        return $value;
    }
    public function getCreatedAtFaAttribute(){return UC($this->attributes[COL_USER_CREATED_AT], U_MILADI_TO_HEJRI);}



}
