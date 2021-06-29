<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;


/**
 * App\Admin
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin username($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin whereInId($value)
 */
class Admin extends ModelEnhanced implements AuthenticatableContract,AuthorizableContract,CanResetPasswordContract {
	use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

	use HasFactory, Notifiable;

	protected $hidden = [
		COL_ADMIN_PASSWORD,
    ];



	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeId($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_ADMINS . '.' . COL_ADMIN_ID, $value);
		}
		return $query;
	}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeName($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_ADMINS . '.' . COL_ADMIN_NAME,"LIKE", "%$value%");
		}
		return $query;
	}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeUsername($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_ADMINS . '.' . COL_ADMIN_USERNAME,"LIKE", "%$value%");
		}
		return $query;
	}



	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeWhereInId($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->whereIn(TBL_ADMINS . '.' . COL_ADMIN_ID, $value);
		}
		return $query;
	}


	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------    accessors & mutator    ---------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	public function getImageAttribute($value) {
		return ModelEnhanced::correctImage($value, PATH_PROFILE_IMAGES);
    }

}
