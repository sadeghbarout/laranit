<?php

namespace App\Models;

use App\Exceptions\ErrorMessageException;
use App\Extras\StatusCodes;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ModelEnhanced
 *
 * @method static \Illuminate\Database\Eloquent\Builder page($loadedCount, $perPage = 20)
 * @method static \Illuminate\Database\Eloquent\Builder page2($page, $perPage = 20)
 * @method static \Illuminate\Database\Eloquent\Builder wheree($column, $value, $default = null)
 * @method static \Illuminate\Database\Eloquent\Builder getWithAllCount()
 * @method static \Illuminate\Database\Eloquent\Builder allCount()
 * @method static \Illuminate\Database\Eloquent\Builder getWithAllCount2()
 * @method static \Illuminate\Database\Eloquent\Builder firstOrError($message=null,$cols=["*"] )
 * @method static \Illuminate\Database\Eloquent\Builder findOrError($id,$message=null,$cols=["*"] )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModelEnhanced query()
 * @mixin \Eloquent
 */
class ModelEnhanced extends Model {

    protected $casts = [
        ];

    //
    public static function checkParameter($parameter, $default = null) {

        if ($parameter === [] || $parameter === null || $parameter === '' || $parameter === $default) {
            return false;
        }
        return true;
    }

    // pagination for APIs
    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopePage($query, $loadedCount, $perPage = 20) {
        if (!$perPage) {
            $perPage = 20;
        }
        if (self::checkParameter($loadedCount)) {
            return $query->offset($loadedCount)->limit($perPage);
        }
        return $query;
    }


    // custom pagination for vue components
    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopePage2($query, $page, $perPage = 20) {
        if (!$perPage) {
            $perPage = 20;
        }
        if (self::checkParameter($page)) {
            $offset = ((int)$page - 1) * $perPage;
            return $query->offset($offset)->limit($perPage);
        }
        return $query;
    }

    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopeWheree($query, $column, $value, $default = null) {
        if ($value === [] || $value === null || $value === '' || $value === $default) {
            return $query->where($column, $value);
        }
        return $query;
    }


    //------------------------------------------------------------------------------------------------------------------------------------
    // this function is usually used in accessor & mutators of any image stored in database  to the return full path to the image
    public static function correctImage($image, $path,$noDefalut=false) {
    	if($noDefalut && $image==null){
    		return $image;
		}
        if ($image == '')
            $image = 'default.png';

        if (substr($image, 0, 4) == 'http') {
            return $image;
        }

        return PREFIX_HTML . $path . $image;
    }


	// ------------------------------------------------------------------------------------------------------------------------------
	public  function scopeFirstOrError($query,$message=null, $cols=["*"]){
		$result =  $query -> first($cols);
		if($result == null)
			throw new ErrorMessageException($message?$message:'آیتم یافت نشد',StatusCodes::HTTP_NOT_FOUND);

		return $result;
	}

	// ------------------------------------------------------------------------------------------------------------------------------
	public  function scopeFindOrError($query,$id,$message=null, $cols=["*"]){
		$id=clear($id);
		$result =  $query ->where('id',$id)-> first($cols);
		if($result == null)
			throw new ErrorMessageException($message?$message:'آیتم یافت نشد',StatusCodes::HTTP_NOT_FOUND);

		return $result;
	}





}








