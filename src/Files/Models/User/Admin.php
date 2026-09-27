<?php

namespace App\Models\User;

use App\Models\ModelEnhanced;
use Colbeh\Access\HasRoles;
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Admin id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Admin name($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Admin username($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Admin whereInId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Admin toDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Admin fromDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\Admin withRoles($cols)
 */
class Admin extends ModelEnhanced implements AuthenticatableContract,AuthorizableContract,CanResetPasswordContract {
	use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
	use HasRoles;
	use HasFactory, Notifiable;

	protected $hidden = [
		COL_ADMIN_PASSWORD,
	];


	protected function casts(): array {
		return [
			COL_ADMIN_SELECTED_COLUMNS => 'array',
		];
	}


	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------    scopes    ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public static function scopeWithRoles($query, $columns = []) {
		$columns[] = COL_ADMIN_ID;
		return $query->with(array('roles' => function ($query) use ($columns) {
			$query->select($columns);
		}));
	}


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

	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeFromDate($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_ADMINS . "." . COL_ADMIN_CREATED_AT,'>=', $value);
		}
		return $query;
	}

	/* @param \Illuminate\Database\Eloquent\Builder $query */
	public function scopeToDate($query, $value) {
		if (ModelEnhanced::checkParameter($value)) {
			return $query->where(TBL_ADMINS . "." . COL_ADMIN_CREATED_AT,'<=', $value);
		}
		return $query;
	}

	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------    functions    ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	public function getSelectedColumns($type) {

		$allSelectedColumns = $this->getAttributeValue(COL_ADMIN_SELECTED_COLUMNS);
		if ($allSelectedColumns == null || !isset($allSelectedColumns[$type]))
			return [];

		return $allSelectedColumns[$type];
	}


	//-----------------------------------------------------------------------------------------------------------------------------
	//-----------------------------------------------    accessors & mutator    ---------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	public function getImageAttribute($value) {
		return ModelEnhanced::correctImage($value, PATH_PROFILE_IMAGES);
	}


	public function getCreatedAtAttribute($value) {
		$this->append(COL_ADMIN_CREATED_AT . '_fa');
		return $value;
	}

	public function getUpdatedAtAttribute($value) {
		$this->append(COL_ADMIN_UPDATED_AT . '_fa');
		return $value;
	}

	public function getLastLoginAttribute($value) {
		$this->append(COL_ADMIN_LAST_LOGIN . '_fa');
		return $value;
	}


	public function getCreatedAtFaAttribute() { return UC($this->attributes[COL_ADMIN_CREATED_AT], U_MILADI_TO_HEJRI); }

	public function getUpdatedAtFaAttribute() { return UC($this->attributes[COL_ADMIN_UPDATED_AT], U_MILADI_TO_HEJRI); }

	public function getLastLoginFaAttribute() { return UC($this->attributes[COL_ADMIN_LAST_LOGIN], U_MILADI_TO_HEJRI); }


	public function getStatusAttribute($value) {
		$this->append(COL_ADMIN_STATUS . '_text');
		$this->append(COL_ADMIN_STATUS . '_color');
		return $value;
	}
	public function getStatusTextAttribute() { return UC($this->attributes[COL_ADMIN_STATUS], "adminStatusText"); }
	public function getStatusColorAttribute() { return UC($this->attributes[COL_ADMIN_STATUS], "adminStatusColor"); }
}
