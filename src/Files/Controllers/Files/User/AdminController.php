<?php

namespace App\Http\Controllers\User;
use App\Extras\Tools;
use App\Extras\Validator;
use App\Http\Controllers\Controller;
use App\Models\User\Admin;
use Colbeh\Access\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

	public function __construct() {
		checkPermissionMiddleWare($this, PERM_ADMIN,['login','doLogin','logout']);
	}


	// check if admin as the root permission
	public static function isRootUser() {
		return Gate::check('permission',PERM_ROOT)?1:0;
	}


	//------------------------------------------------------------------------------------------------------------------------------------
	public function initAdmin() {
		if(!Auth::check()){
			return generateResponse(RES_SUCCESS, ['user' => null]);
		}

		$admin = Auth::user()->only([COL_ADMIN_USERNAME, COL_ADMIN_IMAGE]);

		return generateResponse(RES_SUCCESS, ['user' => $admin]);
	}

	//------------------------------------------------------------------------------------------------------------------------------------
	public function doLogin() {

		Validator::adminDoLoginValidation();

		$username = \request('username');
		$password = \request('password');
		$remember = \request('remember');

		if (Auth::attempt([COL_ADMIN_USERNAME => $username, COL_ADMIN_PASSWORD => $password], $remember)) {
			return generateResponse(RES_SUCCESS, [RK_MESSAGE=> "وارد شدید",RK_REDIRECT=> '//dashboard']);
		}

		return generateResponse(ERR_ERROR_MESSAGE, [RK_MESSAGE=> "نام کاربری یا پسورد اشتباه است"]);
	}


	//------------------------------------------------------------------------------------------------------------------------------------
	public function logout() {

		if (Auth::check()) {
			Auth::logout();
		}
		return redirect('/login');
	}


	// ----------------------------------------------------------------------------------------------------------------------------------
	// admins list
	public function index()
	{
		$id = request("id");
		$username = request("username");
		$name = request("name");
		$rowsCount = request("pageRows",10);
		$page = request("page", 1);
		$sort = request("sort", COL_ADMIN_ID);
		$sortType = request("sort_type", 'desc');


		$builder = Admin::id($id) -> username($username) -> name($name) -> where(COL_ADMIN_USERNAME,'!=','owner');
		$count = $builder->count();
		$items = $builder->orderBy($sort ,$sortType)->page2($page, $rowsCount)->get([COL_ADMIN_ID, COL_ADMIN_USERNAME, COL_ADMIN_NAME]);

		$pageCount = ceil($count/$rowsCount); // count of pages

		return generateResponse(RES_SUCCESS,array(RK_ITEMS => $items , RK_PAGE_COUNT => $pageCount));
	}




	// ----------------------------------------------------------------------------------------------------------------------
	// store new admin
	public function store(Request $request)
	{
		Access::checkAccess(PERM_ROOT);

		Validator::adminStoreValidation();

		$this->doStore();

		return sucBack('ثبت شد','//admin');
	}


	private function doStore() {
		$admin = new Admin();
		$admin[COL_ADMIN_NAME] = request(COL_ADMIN_NAME);
		$admin[COL_ADMIN_USERNAME] = request(COL_ADMIN_USERNAME);
		$admin[COL_ADMIN_PASSWORD] = Hash::make(request(COL_ADMIN_PASSWORD));

		// uploading admin profile image
		if(request() -> file(COL_ADMIN_IMAGE)){
			$file = Tools::uploadAndCompressImage(request() -> file(COL_ADMIN_IMAGE), PATH_PROFILE_IMAGES, 250, 250); // image dimensions = 250 x 250
			$admin[COL_ADMIN_IMAGE] = $file;
		}
		$admin -> save();

	}


	// ----------------------------------------------------------------------------------------------------------------------
	// admin details
	public function show($id)
	{
		Validator::idValidation($id);
		$admin = Admin:: findOrError($id);

		return generateResponse(RES_SUCCESS,["item"=>$admin]);
	}


	// ----------------------------------------------------------------------------------------------------------------------
	// update an admin info
	public function update(Request $request)
	{
		Validator::adminUpdateValidator();
		// todo: check permission
		$admin=$this->updateAndGetAdmin();

		return sucBack('تغییرات ثبت شد','//admin/'.$admin[COL_ADMIN_ID]);

	}


	private function updateAndGetAdmin() {
		$data[COL_ADMIN_NAME] = request(COL_ADMIN_NAME);

		// uploading admin profile image
		if(request() -> file(COL_ADMIN_IMAGE)){
			$file = Tools::uploadAndCompressImage(request() -> file(COL_ADMIN_IMAGE), PATH_PROFILE_IMAGES, 250, 250); // image dimensions = 250 x 250
			$admin[COL_ADMIN_IMAGE] = $file;
		}
		$admin -> save();

		return $admin;
	}


	//----------------------------------------------------------------------------------------------------------------------
	public function profile() {
		$admin=auth()->user()->only([COL_ADMIN_ID,COL_ADMIN_NAME,COL_ADMIN_IMAGE, COL_ADMIN_USERNAME]);
		return generateResponse(RES_SUCCESS,[RK_ITEM=>$admin]);
	}

    //------------------------------------------------------------------------------------------------------------------------------------
    // each admin can upload a profile image in his/her profile
	public function uploadProfileImage() {

		Validator::adminUploadProfileValidation();

		$imageName=Tools::uploadAndCompressImage(request()->file('file'),PATH_UPLOAD.PATH_PROFILE_IMAGES,200,200);

		$oldImage = auth()->user()[COL_ADMIN_IMAGE];
		auth()->user()[COL_ADMIN_IMAGE] = $imageName;
		auth()->user()->save();

		deleteFile($oldImage,PATH_UPLOAD.PATH_PROFILE_IMAGES);

		return generateResponse(RES_SUCCESS, [RK_MESSAGE=> "با موفقیت آپلود شد", RK_IMAGE=> PREFIX_HTML.PATH_PROFILE_IMAGES.$imageName]);
	}


    //------------------------------------------------------------------------------------------------------------------------------------
    // duh
	public function doChangePassword() {

		Validator::adminChangePassValidation();

		$oldPassword = request('old_password');
		$newPassword = request('new_password');

		if (Hash::check($oldPassword, auth()->user()[COL_ADMIN_PASSWORD])) {
			auth()->user()[COL_ADMIN_PASSWORD] = bcrypt($newPassword);
			auth()->user()->save();

			return generateResponse(RES_SUCCESS,[RK_MESSAGE=>"ویرایش شد.",RK_REDIRECT=>'/profile']);
		} else {
			return generateResponse(ERR_ERROR_MESSAGE,[RK_MESSAGE=>"پسورد فعلی شما اشتباه است"]);
		}

	}

}
