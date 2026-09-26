<?php

namespace App\Models\App;

use App\Models\ModelEnhanced;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


/**
 * App\Log
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Log id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Log targetUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Log targetUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Log targetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Log targetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Log date($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Log text($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Log withTargetUser($columns=[])
 */
class Log extends ModelEnhanced {

	use HasFactory;

	public $timestamps = false;

	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------------   relations   ---------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	public function targetUser() {
		return $this->morphTo();
	}

	public function target() {
		return $this->morphTo();
	}

	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------    scopes    ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeWithTargetUser($query, $columns = []) {
		$columns[] = COL_USER_ID;
		return $query->with(array('targetUser' => function ($query) use ($columns) {
			$query->select($columns);
		}));
	}

	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeId($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_LOGS . "." . COL_LOG_ID, $value);
		}
		return $query;
	}

	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeText($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_LOGS . "." . COL_LOG_TEXT, "LIKE", "%$value%");
		}
		return $query;
	}

	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeTargetUserId($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_LOGS . "." . COL_LOG_TARGET_USER_ID, $value);
		}
		return $query;
	}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeTargetUserType($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_LOGS . "." . COL_LOG_TARGET_USER_TYPE, $value);
		}
		return $query;
	}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeTargetId($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_LOGS . "." . COL_LOG_TARGET_ID, $value);
		}
		return $query;
	}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeTargetType($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_LOGS . "." . COL_LOG_TARGET_TYPE, $value);
		}
		return $query;
	}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeDate($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_LOGS . "." . COL_LOG_DATE, $value);
		}
		return $query;
	}

	//-----------------------------------------------------------------------------------------------------------------------------
	//----------------------------------------------------   functions     --------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	public static function registration(string $text, $target, $step=null, $logType=null) {
		$user = Auth::guard('web')->user();
		$type = ENUM_LOG_TARGET_USER_TYPE_ADMIN;
		if(!$user){
			$type = ENUM_LOG_TARGET_USER_TYPE_USER;
			$user = Auth::guard('sanctum')->user();
			if ($user === null && get_class($target) === User::class) {
				$user = $target;
			}
		}

		$item = new Log();
		$item[COL_LOG_TEXT] = $text;
		$item[COL_LOG_STEP] = $step;
		$item[COL_LOG_TYPE] = $logType;

		if($user){
			$item[COL_LOG_TARGET_USER_ID] = $user['id'];
			$item[COL_LOG_TARGET_USER_TYPE] = $type;
		}

		$item[COL_LOG_TARGET_ID] = $target['id'];
		$item[COL_LOG_TARGET_TYPE] = Str::snake(last(explode('\\', get_class($target))));

		$item[COL_LOG_DATE] = Carbon::now();
		$item->save();
	}

	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------    mutator & accessor    ----------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------
	public function getTargetUserTypeAttribute($value) {
		$this->append(COL_LOG_TARGET_USER_TYPE . "_text");

		return $value;
	}


	public function getTargetUserTypeTextAttribute() {
		return UC($this->attributes[COL_LOG_TARGET_USER_TYPE], "logTarget_user_types");
	}


	public function getTargetTypeAttribute($value) {
		$this->append(COL_LOG_TARGET_TYPE . "_text");

		return $value;
	}


	public function getTargetTypeTextAttribute() {
		return UC($this->attributes[COL_LOG_TARGET_TYPE], "logTarget_types");
	}


	public function getDateAttribute($value) {
		$this->append(COL_LOG_DATE . "_fa");
		return $value;
	}


	public function getDateFaAttribute() {
		return UC($this->attributes[COL_LOG_DATE], U_MILADI_TO_HEJRI);
	}


	public function getCreatedAtAttribute($value) {
		$this->append(COL_LOG_CREATED_AT . "_fa");
		return $value;
	}


	public function getCreatedAtFaAttribute() {
		return UC($this->attributes[COL_LOG_CREATED_AT], U_MILADI_TO_HEJRI);
	}


	public function getUpdatedAtAttribute($value) {
		$this->append(COL_LOG_UPDATED_AT . "_fa");
		return $value;
	}


	public function getUpdatedAtFaAttribute() {
		return UC($this->attributes[COL_LOG_UPDATED_AT], U_MILADI_TO_HEJRI);
	}
}
