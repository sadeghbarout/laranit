<?php

namespace App\Http\Controllers\App;

use App\Exceptions\ErrorMessageException;
use App\Extras\StatusCodes;
use App\Extras\Validator;
use App\Http\Controllers\Controller;
use Colbeh\Access\Access;
use Colbeh\Access\Models\Permission;
use Colbeh\Access\Models\Role;

class RoleController extends Controller {


	public function index() {
		$items = Access::rolesList();
		return generateResponse(RES_SUCCESS, [RK_ITEMS => $items]);
	}


	// --------------------------------------------------------------------------------------------------------------------------
	public function show($id) {
		Validator::idValidation($id);
		$item = Access::getRole($id);
		$item->permissions;
		$permissions = Access::permissionsList()->groupBy('section');
		return generateResponse(RES_SUCCESS, [RK_ITEM => $item, RK_PERMISSIONS => $permissions]);
	}


	// --------------------------------------------------------------------------------------------------------------------------
	public function permissionToggle() {
		Validator::rolePermissionToggleValidator();

		$roleId = request('role_id');
		$permId = request('permission_id');

		Access::permissionToggle($roleId, $permId);

		return generateResponse(RES_SUCCESS);
	}


	// --------------------------------------------------------------------------------------------------------------------------
	public function store() {
		Validator::roleStoreValidator();

		$name = request('name');
		$desc = request('desc');

		$role = Access::roleStore($name, $desc, []);

		return generateResponse(RES_SUCCESS, [RK_ITEM => $role, RK_REDIRECT => '/role']);
	}


	// --------------------------------------------------------------------------------------------------------------------------
	public function update($id) {
		Validator::idValidation($id);
		Validator::roleUpdateValidator();

		$name = request('name');
		$desc = request('desc');

		$role = Access::roleUpdate($id, $name, $desc, null);

		return generateResponse(RES_SUCCESS, [RK_ITEM => $role, RK_REDIRECT => '/role']);
	}


	// --------------------------------------------------------------------------------------------------------------------------
	public function destroy($id) {
		Validator::idValidation($id);
		$role = Role::where('id', $id)->first();
		if ($role == null)
			throw new ErrorMessageException("نقش یافت نشد", StatusCodes::HTTP_NOT_FOUND);

		$adminsCount = $role->admins()->count();
		if ($adminsCount > 0)
			throw new ErrorMessageException("این نقش به $adminsCount مدیر متصل است. ابتدا نقش را از آن مدیر حذف نمایید", StatusCodes::HTTP_CONFLICT);

		$role->delete();
		return generateResponse(RES_SUCCESS, [RK_REDIRECT => '/role']);
	}

// ------------------------------------------------------------------------------------------------------
	// returning all permissions except for root permission
	public function permissions() {

		$items = Permission::get()->groupBy('section');

		return generateResponse(RES_SUCCESS, array(RK_ITEMS => $items));
	}
}
