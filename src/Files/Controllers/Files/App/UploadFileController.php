<?php

namespace App\Http\Controllers\App;

use App\Extras\Validator;
use App\Http\Controllers\Controller;
use App\Models\App\UploadFile;

class UploadFileController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $rowsCount = request("rows_count", 10);
        $page = request("page", 1);
        $id = request('id');
        $userId = request('user_id');
        $type = request('type');
        $targetId = request('target_id');
        $targetType = request('target_type');


        $builder = UploadFile::id($id)->userId($userId)->type($type)->targetId($targetId)->targetType($targetType);
        $count = $builder->count();
        $items = $builder->orderByDesc(COL_UPLOAD_FILE_ID)->page2($page, $rowsCount)->get();

        $pageCount = ceil($count / $rowsCount);

        return generateResponse(RES_SUCCESS, array('items' => $items, 'page_count' => $pageCount));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $item = UploadFile::findOrError($id);
        return generateResponse(RES_SUCCESS, ["item" => $item]);
    }

    /**
     * Display the specified resource.
     */
    public function getUploadFiles($userId = null) {
        Validator::uploadFileGetRequestLogsValidator();

        $targetId = request('target_id');
        $target = request('target');

        $files = UploadFile::getList($target, $targetId, userId: $userId);

        return generateResponse(RES_SUCCESS, ["item" => $files]);
    }
}
