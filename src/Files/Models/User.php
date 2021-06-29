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

    public $fillable = [COL_USER_IS_SEEN,COL_USER_INVITER_ID];
	protected $hidden = [
		COL_ADMIN_PASSWORD,
    ];



	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------   relations   ---------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------


	public function roles() {
		return $this->belongsToMany(Role::class);
	}

	public function cards() {
		return $this->hasMany(Card::class);
	}

	public function carOrders() {
		return $this->hasMany(CarOrder::class);
	}

	public function consultations() {
		return $this->hasMany(Consultation::class);
	}

	public function highTransactions() {
		return $this->hasMany(HighTransaction::class);
	}

	public function notifications() {
		return $this->hasMany(Notification::class);
	}

	public function payments() {
		return $this->hasMany(Payment::class);
	}

	public function productOrder() {
		return $this->hasMany(ProductOrder::class);
	}

	public function transactions() {
		return $this->hasMany(Transaction::class);
	}

	public function translations() {
		return $this->hasMany(Translation::class);
	}


	public function payment() {
		return $this->morphOne(Payment::class, 'target');
    }

	public function securityQuestion() {
		return $this->belongsTo(SecurityQuestion::class);
	}

	public function country() {
		return $this->belongsTo(Country::class);
    }


	public function statusHistory() {
		return $this->hasMany(UserStatus::class);
	}

	public function chats() {
		return $this->hasMany(Chat::class);
    }

    public function exchanger() {
		return $this->hasOne(Exchanger::class);
	}

	public function investor() {
		return $this->hasOne(Investor::class);
	}

	public function favoriteExchangers() {
		return $this->belongsToMany(Exchanger::class, TBL_USER_EXCHANGER_FAVORITE);
	}

	public function authSteps() {
		return $this->hasMany(UserAuthSteps::class);
	}

	//-----------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------   scopes   -----------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeWithCountry($query, $columns = []) {
		$columns[] = COL_COUNTRY_ID;
		return $query->with(array('country' => function ($query) use ($columns) {
			$query->select($columns);
		}));
	}

	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeWithExchangerPlan($query, $columns = []) {
		$columns[] = COL_COUNTRY_ID;
		return $query->with(array('exchanger.plan' => function ($query) use ($columns) {
			$query->select($columns);
		}));
	}


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
	public function scopePhoneCode($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_PHONE_CODE, $value);
		}
		return $query;
	}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeTransactionPin($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_TRANSACTION_PIN, $value);
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
	public function scopeLastName($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_LAST_NAME,"LIKE", "%$value%");
		}
		return $query;
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeName($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(function($q) use($value){
                $q -> where(COL_USER_FIRST_NAME,'LIKE',"%$value%") -> orWhere(COL_USER_LAST_NAME,'LIKE',"%$value%");
            });
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
	public function scopeCountryId($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_USERS . '.' . COL_USER_COUNTRY_ID, $value);
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
    public function scopeWithSecurityQuestion($query, $cols){
        $cols[] = COL_SECURITY_QUESTION_ID;
        return $query->with(['securityQuestion' => function ($query) use($cols) {
            $query->select($cols) -> withTrashed();
        }]);
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

    /* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeInviterId($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_USERS . '.' . COL_USER_INVITER_ID, $value);
        }
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeType($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_USERS . '.' . COL_USER_TYPE, $value);
        }
    }

    /* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeJustBranches($query) {
		return $query->whereHas('exchanger',function($q){
			return $q->where(COL_EXCHANGER_IS_BRANCH,1);
		});
    }

  /* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeJustInstants($query) {
		return $query->whereHas('exchanger',function($q){
			return $q->where(COL_EXCHANGER_INSTANT_DELIVERY_CODE,'!=',null);
		});
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public static function scopeSignupStep($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_USERS . '.' . COL_USER_SIGNUP_STEP, $value);
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

    public function hasInviter(){
		if($this[COL_USER_INVITER_ID] != null && $this[COL_USER_INVITER_ID] != 0)
			return true;

		return false;
	}


	//---------------------------------------------------------------------------------------------------------
	public function checkUserLimits($wantedPrice,$isTransaction=false) {
    	$userId=$this[COL_USER_ID];
		$authSteps=$this->authSteps()->get();
		$phoneAuthStep=$authSteps->where(COL_USER_AUTH_STEP_STEP,ENUM_USER_AUTH_STEP_STEP_PHONE)->where(COL_USER_AUTH_STEP_STATUS,ENUM_USER_AUTH_STEP_STATUS_APPROVED)->first();
		if($phoneAuthStep==null){
			throw new ErrorMessageException('برای هر تراکنشی ابتدا باید شماره موبایل خود را تایید کرده باشید. برای این کار به پروفایل و به بخش احراز هویت مراجعه نمایید');
		}

		$licenseAuthStep=$authSteps->where(COL_USER_AUTH_STEP_STEP,ENUM_USER_AUTH_STEP_STEP_LICENSE_IMAGE)->where(COL_USER_AUTH_STEP_STATUS,ENUM_USER_AUTH_STEP_STATUS_APPROVED)->first();
		if($licenseAuthStep==null){
			$bankInfoAuthStep=$authSteps->where(COL_USER_AUTH_STEP_STEP,ENUM_USER_AUTH_STEP_STEP_BANK_INFO)->where(COL_USER_AUTH_STEP_STATUS,ENUM_USER_AUTH_STEP_STATUS_APPROVED)->first();
			$transCount=WalletTransaction::userId($userId)->where(COL_WALLET_TRANS_AMOUNT,'<',0)->count();
			if($bankInfoAuthStep==null){
				if($transCount>0)
					throw new ErrorMessageException('حساب شما محدود می باشد . جهت برطرف کردن محدودیت، از طریق پروفایل، مراحل احراز هویت خود را تکمیل نمایید(1).',StatusCodes::HTTP_NOT_ACCEPTABLE);

				if($wantedPrice>5000000)
					throw new ErrorMessageException('حساب شما به 5 میلیون تومان برای یک بار محدود می باشد . جهت برطرف کردن محدودیت، از طریق پروفایل، مراحل احراز هویت خود را تکمیل نمایید(2).',StatusCodes::HTTP_NOT_ACCEPTABLE);

			}else{
				if($transCount>0)
					throw new ErrorMessageException('حساب شما محدود می باشد . جهت برطرف کردن محدودیت، از طریق پروفایل، مراحل احراز هویت خود را تکمیل نمایید(3).',StatusCodes::HTTP_NOT_ACCEPTABLE);

				if($wantedPrice>25000000)
					throw new ErrorMessageException('حساب شما به 25 میلیون تومان برای یک بار محدود می باشد . جهت برطرف کردن محدودیت، از طریق پروفایل، مراحل احراز هویت خود را تکمیل نمایید(4).',StatusCodes::HTTP_NOT_ACCEPTABLE);

			}
		}else{
			if($isTransaction){
				$bankAPIAuthStep=$authSteps->where(COL_USER_AUTH_STEP_STEP,ENUM_USER_AUTH_STEP_STEP_BANK_API)->where(COL_USER_AUTH_STEP_STATUS,ENUM_USER_AUTH_STEP_STATUS_APPROVED)->first();

				$todayTransSum=abs(WalletTransaction::userId($userId)->targetType(ENUM_WALLET_TRANS_TARGET_TYPE_TRANSACTION)->where('created_at','>',getServerDate())->sum(COL_WALLET_TRANS_AMOUNT));
				if($bankAPIAuthStep==null){
					if($wantedPrice+$todayTransSum>50000000)
						throw new ErrorMessageException('حساب شما به 50 میلیون تومان در روز محدود می باشد . جهت برطرف کردن محدودیت، با پشتیبانان توایز تماس حاصل فرمایید.',StatusCodes::HTTP_NOT_ACCEPTABLE);

				}else{
					if($wantedPrice+$todayTransSum>200000000)
						throw new ErrorMessageException('حساب شما به 200 میلیون تومان در روز محدود می باشد . جهت برطرف کردن محدودیت، با پشتیبانان توایز تماس حاصل فرمایید.',StatusCodes::HTTP_NOT_ACCEPTABLE);

				}
			}else{
				// no limit
			}
		}

		return true;

	}


	//---------------------------------------------------------------------------------------------------------
    public function checkBalance($price){
		if($this[COL_USER_WALLET]<$price){
			throw new ErrorMessageException("موجودی کیف پول شما کافی نمی باشد.$price",StatusCodes::HTTP_NOT_ACCEPTABLE);
		}
		return false;
	}


	//---------------------------------------------------------------------------------------------------------
    public function decreaseBalance($price){
		$this[COL_USER_WALLET]-=$price;
		$this->save();
	}


	//---------------------------------------------------------------------------------------------------------
    public function increaseBalance($price){
		$this[COL_USER_WALLET]+=$price;
		$this->save();
	}

    //---------------------------------------------------------------------------------------------------------
    public function checkPinCode($pinCode){
	    if($this[COL_USER_TRANSACTION_PIN]!=$pinCode)
            throw new ErrorMessageException('پین کد وارد شده صحیح نمی باشد.', StatusCodes::HTTP_NOT_ACCEPTABLE);
    }



    //-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------    accessors & mutator    ---------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	public function getProfileImageAttribute($value) {
		return $this->correctImage($value, PATH_PROFILE_IMAGES);
    }


	public function getLicenseImageAttribute($value) {
		return $this->correctImage($value, PATH_PROFILE_IMAGES);
    }


	public function getPassportImageAttribute($value) {
		return $this->correctImage($value, PATH_PROFILE_IMAGES);
	}

	public function getResidencyFrontImageAttribute($value) {
		return $this->correctImage($value, PATH_PROFILE_IMAGES);
	}

	public function getResidencyBackImageAttribute($value) {
		return $this->correctImage($value, PATH_PROFILE_IMAGES);
	}


    // decrypting user wallet to get a human readable number
	public function getWalletAttribute($value) {
        return Tools::decryptData($value);
	}

    // user wallet is encrypted and stored in databse (for security reasons)
	public function setWalletAttribute($value) {
		$this->attributes[COL_USER_WALLET] = Tools::encryptData($value);
	}

	public function getStatusAttribute($value){
        $this -> append(COL_USER_STATUS.'_text');
        $this -> append(COL_USER_STATUS.'_color');
        return $value;
    }
    public function getStatusTextAttribute(){return UC($this->attributes[COL_USER_STATUS], U_USER_STATUSES);}
    public function getStatusColorAttribute(){return UC($this->attributes[COL_USER_STATUS], U_USER_STATUS_COLOR);}




    public function getSignupStepAttribute($value){
        $this -> append(COL_USER_SIGNUP_STEP.'_text');
        return $value;
    }
    public function getSignupStepTextAttribute(){return UC($this->attributes[COL_USER_SIGNUP_STEP], U_USER_SIGNUP_STEPS);}




    public function getGenderAttribute($value){
        $this -> append(COL_USER_GENDER.'_text');
        return $value;
    }
    public function getGenderTextAttribute(){return UC($this->attributes[COL_USER_GENDER], U_USER_GENDER);}




    public function getTypeAttribute($value){
        $this -> append(COL_USER_TYPE.'_text');
        return $value;
    }
    public function getTypeTextAttribute(){return UC($this->attributes[COL_USER_TYPE], U_USER_TYPE);}




    public function getSignupCompleteDateAttribute($value){
        $this -> append(COL_USER_SIGNUP_COMPLETE_DATE.'_fa');
        return $value;
    }
    public function getSignupCompleteDateFaAttribute(){return UC($this->attributes[COL_USER_SIGNUP_COMPLETE_DATE], U_MILADI_TO_HEJRI);}



    public function getCreatedAtAttribute($value){
        $this -> append(COL_USER_CREATED_AT.'_fa');
        return $value;
    }
    public function getCreatedAtFaAttribute(){return UC($this->attributes[COL_USER_CREATED_AT], U_MILADI_TO_HEJRI);}



}
