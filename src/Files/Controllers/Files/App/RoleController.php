<?php

namespace App\Http\Controllers\App;

use App\Exceptions\ErrorMessageException;
use App\Extras\StatusCodes;
use App\Extras\Validator;
use App\Http\Controllers\Controller;
use App\Models\App\Log;
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
		$item->permissions()->oldest('order')->get();

		$permissions = Access::permissionsList()
			->groupBy('section')
			->map(fn ($permissions) => $permissions->sortBy('order')->values());

		return generateResponse(RES_SUCCESS, [RK_ITEM => $item, RK_PERMISSIONS => $permissions]);
	}


	// --------------------------------------------------------------------------------------------------------------------------
    public function permissionToggle() {
        Validator::rolePermissionToggleValidator();

        $roleId = request('role_id');
        $permId = request('permission_id');

        $permission = Permission::where('id', $permId)->where('section', '!=', 'bpms')->first();
        if(!$permission){
            throw new ErrorMessageException("دسترسی یافت نشد.",StatusCodes::HTTP_NOT_FOUND);
        }

        $role = Access::permissionToggle($roleId, $permId);
        $hasPermissions = $role->permissions()->where('permission_id', $permId)->exists();

        $roleDesc = $role->desc;

        $action = $hasPermissions ? 'اضافه شد' : 'حذف شد';
        $logText = sprintf(
            'دسترسی %s (شناسه: %d) برای نقش %s (شناسه: %d) %s.',
            $permission['desc'],
            $permId,
            $roleDesc,
            $roleId,
            $action
        );
        Log::registration($logText, $role);

        return generateResponse(RES_SUCCESS);
    }

	// --------------------------------------------------------------------------------------------------------------------------
    public function store() {
        Validator::roleStoreValidator();

        $name = request('name');
        $desc = request('desc');

        $role = Access::roleStore($name, $desc, []);
        Log::registration(sprintf('نقش %s ایجاد شد', $role->desc), $role);

        return generateResponse(RES_SUCCESS, [RK_ITEM => $role, RK_REDIRECT => '/role']);
    }


	// --------------------------------------------------------------------------------------------------------------------------
    public function update($id) {
        Validator::idValidation($id);
        Validator::roleUpdateValidator();

        $name = request('name');
        $desc = request('desc');
        $oldRole = Role::find($id);

        $role = Access::roleUpdate($id, $name, $desc, null);
        Log::registration(
            sprintf(
                'نقش از "%s" به "%s" ویرایش شد.',
                $oldRole->name . ' (' . $oldRole->desc . ')',
                $role->name . ' (' . $role->desc . ')'
            ),
            $role
        );
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

        Log::registration(sprintf('نقش %s حذف شد', $role->desc), $role);

        return generateResponse(RES_SUCCESS, [RK_REDIRECT => '/role']);
    }

// ------------------------------------------------------------------------------------------------------
	// returning all permissions except for root permission
	public function permissions() {

		$items = Permission::get()->groupBy('section');

		return generateResponse(RES_SUCCESS, array(RK_ITEMS => $items));
	}

// ------------------------------------------------------------------------------------------------------
	public function getPermissionData($id) {
		Validator::idValidation($id);

		$permission = Permission::find($id);
		if (!$permission) {
			throw new ErrorMessageException('دسترسی یافت نشد', StatusCodes::HTTP_NOT_FOUND);
		}

		$admins = [];
		$roles = $permission->roles()->get(['id', 'name', 'desc']);
		foreach ($roles as $role) {
			$adminList = $role->admins()->get([COL_ADMIN_ID, COL_ADMIN_NAME, COL_ADMIN_USERNAME]);
			$admins = [...$admins, ...$adminList];
		}

		return generateResponse(RES_SUCCESS, ['admins' => $admins, 'roles' => $roles]);
	}

	// ------------------------------------------------------------------------------------------------------
	public function findAdminByRole($id) {
		$role = Role::where('id', $id)->first();
		if ($role == null)
			throw new ErrorMessageException("نقش یافت نشد", StatusCodes::HTTP_NOT_FOUND);


		$admins = $role->admins()->get([COL_ADMIN_ID, COL_ADMIN_NAME, COL_ADMIN_USERNAME]);

		return generateResponse(RES_SUCCESS, ['admins' => $admins]);
	}
}
