<?php

namespace Colbeh\Laranit;

class Helper {

	public static function getServerDateTime() {
		date_default_timezone_set('Asia/tehran');
		return date("Y-m-d H:i:s");
	}


	public static function getAdminPermissions($admin) {
		global $adminRoles;
		global $adminPermissions;

		if($adminRoles==null){
			$adminRoles=$admin->roles()->with('permissions')->get();
			$roles=$adminRoles->pluck('permissions');
			foreach ($roles AS $permissionsCollections){
				foreach ($permissionsCollections AS $permission){
					$adminPermissions[]=$permission;
				}
			}
			$adminPermissions= collect($adminPermissions);
		}

		return $adminPermissions;
	}

}