<?php

namespace App\Models\App;

use App\Extras\StorageHelper;
use App\Models\ModelEnhanced;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


/**
 * App\UploadFile
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\UploadFile id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\UploadFile userId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\UploadFile type($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\UploadFile targetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\App\UploadFile targetType($value)
 */
class UploadFile extends ModelEnhanced {

    use HasFactory;

    //-----------------------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------   relations   ---------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------------------

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function target() {
        return $this->morphTo();
    }

    //-----------------------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------    scopes    ----------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------------------

    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopeId($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_UPLOAD_FILES . "." . COL_UPLOAD_FILE_ID, $value);
        }
        return $query;
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopeUserId($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_UPLOAD_FILES . "." . COL_UPLOAD_FILE_USER_ID, $value);
        }
        return $query;
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopeType($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_UPLOAD_FILES . "." . COL_UPLOAD_FILE_TYPE, $value);
        }
        return $query;
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopeTargetId($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_UPLOAD_FILES . "." . COL_UPLOAD_FILE_TARGET_ID, $value);
        }
        return $query;
    }


    /* @param \Illuminate\Database\Eloquent\Builder $query */
    public function scopeTargetType($query, $value) {
        if (ModelEnhanced::checkParameter($value)) {
            return $query->where(TBL_UPLOAD_FILES . "." . COL_UPLOAD_FILE_TARGET_TYPE, $value);
        }
        return $query;
    }

    //-----------------------------------------------------------------------------------------------------------------------------
    //----------------------------------------------------   functions    ---------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------------------

    public static function saveFile($userId, $path, $file, $type, $target, array $extensions = []): string {
        $filename = (new StorageHelper())->extensions($extensions)->put($path, $file);

        $uploadFile = new UploadFile();
        $uploadFile[COL_UPLOAD_FILE_USER_ID] = $userId;
        $uploadFile[COL_UPLOAD_FILE_FILE] = basename($filename);
        $uploadFile[COL_UPLOAD_FILE_TYPE] = $type;
        $uploadFile[COL_UPLOAD_FILE_TARGET_ID] = $target['id'];
        $uploadFile[COL_UPLOAD_FILE_TARGET_TYPE] = Str::snake(last(explode('\\', get_class($target))));
        $uploadFile->save();

        return $filename;
    }

    public static function getList($targetType, $targetId, $type=null, $userId=null) {
        return UploadFile::userId($userId)->type($type)
            ->where(COL_UPLOAD_FILE_TARGET_TYPE, $targetType)
            ->where(COL_UPLOAD_FILE_TARGET_ID, $targetId)->get([COL_UPLOAD_FILE_ID, COL_UPLOAD_FILE_FILE, COL_UPLOAD_FILE_TYPE, COL_UPLOAD_FILE_TARGET_ID, COL_UPLOAD_FILE_TARGET_TYPE]);
    }

    public static function removeFile($record, $path): void {
        (new StorageHelper())->delete($path, basename($record[COL_UPLOAD_FILE_FILE]));
        $record->delete();
    }

    //-----------------------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------    mutator & accessor    ----------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------------------
    public function getTypeAttribute($value) {
        $this->append(COL_UPLOAD_FILE_TYPE . "_text");

        return $value;
    }


    public function getTypeTextAttribute() {
        return UC($this->attributes[COL_UPLOAD_FILE_TYPE], "uploadfileTypes");
    }


    public function getTargetTypeAttribute($value) {
        $this->append(COL_UPLOAD_FILE_TARGET_TYPE . "_text");

        return $value;
    }


    public function getTargetTypeTextAttribute() {
        return UC($this->attributes[COL_UPLOAD_FILE_TARGET_TYPE], "uploadfileTarget_types");
    }


    public function getCreatedAtAttribute($value) {
        $this->append(COL_UPLOAD_FILE_CREATED_AT . "_fa");
        return $value;
    }


    public function getCreatedAtFaAttribute() {
        return UC($this->attributes[COL_UPLOAD_FILE_CREATED_AT], U_MILADI_TO_HEJRI);
    }


    public function getUpdatedAtAttribute($value) {
        $this->append(COL_UPLOAD_FILE_UPDATED_AT . "_fa");
        return $value;
    }


    public function getUpdatedAtFaAttribute() {
        return UC($this->attributes[COL_UPLOAD_FILE_UPDATED_AT], U_MILADI_TO_HEJRI);
    }

    public function getFileAttribute($value) {
        if($this[COL_UPLOAD_FILE_TARGET_TYPE] === ENUM_UPLOAD_FILE_TYPE_ID_CARD_IMAGE && $this[COL_UPLOAD_FILE_TYPE] === ENUM_UPLOAD_FILE_TYPE_ID_CARD_IMAGE){
            return $this->correctImage($value, PATH_USER_ID_CARD);
        }

        return $value;
    }
}
