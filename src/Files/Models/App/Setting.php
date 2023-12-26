<?php

namespace App\Models\App;
use App\Models\ModelEnhanced;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * App\Setting
 *
  * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Setting id($value)
  * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\Setting name($value)

		*/
class Setting extends ModelEnhanced
{
    use HasFactory;
	public $timestamps=false;
    public $fillable = [COL_SETTING_NAME, COL_SETTING_VALUE];




	//-----------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------   functions   --------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

    // getting all settings
	public static function allSettings($keys=[]) {
		if($keys==[]){
			$settings = Setting::all();
		}else{
			$settings = Setting::whereIn(COL_SETTING_NAME,$keys)->get();
		}

		$settings_array = array();
		//itterate all settings
		foreach ($settings AS $k1 => $k2) {
			$settings_array[$k2[COL_SETTING_NAME]] = $k2[COL_SETTING_VALUE];
		}
		return $settings_array;
	}


    // getting a single setting
	public static function getSetting($name) {
		$setting = Setting::where(COL_SETTING_NAME,$name)->first();
		if($setting){
			return $setting[COL_SETTING_VALUE];
		}
		return '';
	}



	//-----------------------------------------------------------------------------------------------------------------------------
	//------------------------------------------------------   scopes   -----------------------------------------------------------
	//-----------------------------------------------------------------------------------------------------------------------------

	/* @param \Illuminate\Database\Eloquent\Builder $query */
		public function scopeId($query, $value) {
			if (ModelEnhanced::checkParameter($value)) {
				return $query->where(TBL_SETTINGS.".".COL_SETTING_ID,$value);
			}
			return $query;
		}


	/* @param \Illuminate\Database\Eloquent\Builder $query */
		public function scopeName($query, $value) {
			if (ModelEnhanced::checkParameter($value)) {
				return $query->where(TBL_SETTINGS.".".COL_SETTING_NAME,$value);
			}
			return $query;
		}


}
