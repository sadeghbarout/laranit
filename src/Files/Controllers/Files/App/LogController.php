<?php

namespace App\Http\Controllers\App;

use App\Extras\Tools;
use App\Http\Controllers\Controller;
use App\Models\App\Log;
use Colbeh\Access\Access;

class LogController extends Controller {

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $rowsCount = request("rows_count", 10);
        $page = request("page", 1);
        $id = request('id');
        $targetUserId = request('target_user_id');
        $targetUserType = request('target_user_type');
        $targetId = request('target_id');
        $targetType = request('target_type');
        $text = request('text');
        $betweenDate = request('between_date');
        $export = request('export', 0);
        $sort = request("sort", []);
        $filters = request("filters", []);


        $builder = Log::with('targetUser')
            ->sort($sort)->filters($filters)
            ->id($id)->text($text)->targetUserId($targetUserId)->targetUserType($targetUserType)->targetId($targetId)->targetType($targetType)->betweenDate(COL_LOG_DATE,$betweenDate);
        if($export == 1)
            return $this->export($builder);

        $count = $builder->count();
        $items = $builder->page2($page, $rowsCount)->get();

        $pageCount = ceil($count / $rowsCount);

        return generateResponse(RES_SUCCESS, array('items' => $items, 'page_count' => $pageCount, 'count' => $count));
    }

    private function export($builder){
        Access::checkAccess(PERM_LOGS_EXCEL);

        ini_set('memory_limit', '2048M');
        set_time_limit(1000);

        $items = Tools::getExcelDataLazy($builder);

        $colsValue = [
            ['label' => 'شناسه', 'value' => COL_LOG_ID, 'width' => 160],
            ['label' => 'متن', 'value' => COL_LOG_TEXT, 'width' => 240],
            ['label' => 'شناسه کاربر', 'value' => COL_LOG_TARGET_USER_ID, 'width' => 160],
            ['label' => 'نوع کاربر', 'value' => COL_LOG_TARGET_USER_TYPE . '_text', 'width' => 160],
            ['label' => 'شناسه فرایند', 'value' => COL_LOG_TARGET_ID, 'width' => 160],
            ['label' => 'فرایند', 'value' => COL_LOG_TARGET_TYPE . '_text', 'width' => 160],
            ['label' => 'تاریخ ثبت', 'value' => COL_LOG_DATE . '_fa', 'width' => 160],
        ];

        $url = Tools::excelFile($items, $colsValue, "log_template");
        return generateResponse(RES_SUCCESS,[RK_LINK => $url]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        $item = Log::with('targetUser')->findOrError($id);
        return generateResponse(RES_SUCCESS, ["item" => $item]);
    }
}
