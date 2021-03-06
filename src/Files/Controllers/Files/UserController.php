<?php

namespace App\Http\Controllers;
use App\Extras\Tools;
use App\Models\User;
use Auth;
use Hash;
use App\Exceptions\ErrorMessageException;

class UserController extends Controller
{
	public function __construct() {
		checkPermissionMiddleWare($this, PERM_USER);
	}


    // ----------------------------------------------------------------------------------------------------------------
    public function index(){
        $id = request("id");
        $phoneNumber = request("phone_number");
        $status = request("status");
        $name = request("name");
        $page = request("page", 1);
        $pageRows = request("pageRows", 10);
        $sort = request("sort", COL_USER_ID);
        $sortType = request("sort_type", 'desc');
        $inviterId = null;

		$itemsBuilder = User:: id($id) -> status($status) -> phonenumber($phoneNumber) -> name($name) -> inviterId($inviterId);

		$count = $itemsBuilder->count();
		$items = $itemsBuilder->orderBy($sort,$sortType)->page2($page, $pageRows)->get([COL_USER_ID, COL_USER_FIRST_NAME,  COL_USER_PHONENUMBER, COL_USER_STATUS, COL_USER_IS_SEEN]);
        $pageCount = ceil($count/$pageRows);

		return generateResponse(RES_SUCCESS,array(RK_ITEMS => $items , RK_PAGE_COUNT => $pageCount));
    }


    // ----------------------------------------------------------------------------------------------------------------------------------------------------
    public function show($id){
        $user = User::findOrError($id);

        if($user[COL_USER_IS_SEEN] == 0)
            User::id($id) -> update([COL_USER_IS_SEEN => 1]);

        return generateResponse(RES_SUCCESS,[RK_ITEM=>$user]);
    }


    // ------------------------------------------------------------------------------------------------------
    // editing specific columns
    public function editing($id){
        $user = User::findOrError($id);

		$cases=[
			'status'=>function($model,$value){
				$model[COL_USER_STATUS] = $value;

				if($value==ENUM_USER_STATUS_VALIDATED){
                    $model[COL_USER_COMMENT] = '';

                    $messageToBeSentToUser = "???????? ?????? ?????????? ????";
                    Tools::sendNotification($messageToBeSentToUser  , "?????????? ????????",$model,"",['action'=>'account_validated']);
                }
                elseif($value==ENUM_USER_STATUS_DEFECTED){
                    $comment = request(COL_USER_COMMENT);
                    if($comment == null)
                        throw new ErrorMessageException('???????? ?????????????? ???? ???????? ????????');

                    $model[COL_USER_COMMENT] = $comment;
                }

                if($value!=ENUM_USER_STATUS_VALIDATED){
                    $statusText = UC($value, U_USER_STATUSES);

                    $messageToBeSentToUser = "???????? ?????? ???? ?????????? ".$statusText." ???????? ????????" ;
                    Tools::sendNotification($messageToBeSentToUser  , "?????????? ?????????? ????????",$model,"");
                }

				return $model;
            },
		];

        return $this->doEditing($user,$cases);
    }



}
