<?php
//  this file is kinda the heart of this project , it contains all of constants used in this project , all COL_ , ENUM_ , S_ , PATH_ , ....

define('PREFIX_ADMIN','admin');
define('PREFIX_API','api');

define('SITE_URL',config('app.base_url'));
define('SITE_URL_ADMIN',PREFIX_ADMIN.'.'.config('app.base_url'));
define('SITE_URL_API',PREFIX_API.'.'.config('app.base_url'));

define('PATH_IMAGES','images/');
//define('PATH_APPS', 'files/apps/');
define('PATH_PROFILE_IMAGES','images/profiles/');
define('PATH_TMP','files/tmp/');

// PATH_UPLOAD should addressing from public folder
if( config('app.env') === 'local'){
	define('PATH_UPLOAD', '../public/');
	define('PREFIX_HTML', 'http://' . SITE_URL_ADMIN . '/');
}else{
	define('PATH_UPLOAD','');
	define('PREFIX_HTML','');
}



// ------------------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------      settings      -------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------
define('S_MINIMUM_APP_VERSION_ANDROID', 'minimumAppVersionAndroid');
define('S_LATEST_APP_VERSION_ANDROID', 'latestAppVersionAndroid');
define('S_SETTINGS_VERSION', 'settings_version');

// ------------------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------      APP LINKS      -------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------

define('APP_LINK_PRE', 'twise://twise.com/');
//define('APP_LINK_PRODUCTS', 'products');// /product?cat_id=33

// ------------------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------      Responses      -------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------


define('RES_SUCCESS', 'success');

define('ERR_ERROR', 'error');
define('ERR_UNDER_MAINTENANCE', 'under_maintenance'); // the server is under maintenance
define('ERR_ERROR_MESSAGE', 'error_message'); // for sendig message of error directly
define('ERR_MINIMUM_APP_VERSION', 'minimum_app_version'); // reach to minimum app version
define('ERR_NO_TOKEN', 'no_token');

// ------------------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------    RESPONSE KEYS      -------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------
define('RK_API_TOKEN', 'api_token');
define('RK_RESULT', 'result');
define("RK_MESSAGE", "message");
define("RK_REDIRECT", "redirect");
define("RK_ITEMS", "items");
define("RK_ITEM", "item");
define('RK_SETTINGS', 'settings');
define('RK_SETTING', 'setting');
define('RK_IMAGE', 'setting');
define('RK_HAS_ROOT_PERMISSION', 'has_root_permission');
define('RK_PAGE_COUNT', 'page_count');
define('RK_UPDATE_URL', 'update_url');
define('RK_USER', 'user');
define('RK_ADMIN_PERMISSIONS', 'admin_permissions');
define('RK_PERMISSIONS', 'permissions');



// ------------------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------      PARAMS      -------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------
define('H_AUTHORIZATION', 'authorization');
define('P_REQUEST', 'request');
define('P_SETTINGS_VERSION', 'residency_type');
define('P_APP_VERSION', 'app_version');
define('P_MAC', 'mac');
define('P_OS', 'os');
define('P_DEVICE_INFO', 'device_info');
define('P_FIREBASE_TOKEN', 'firebase_token');
define('P_ID', 'id');
define('P_LOADED_COUNT', 'loaded_count');
define('P_PASSWORD', 'password');
define('P_LANG', 'lang');


// ------------------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------      DATABASES     --------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------


define('TBL_ADMINS', 'admins');
define('COL_ADMIN_ID', 'id');
define('COL_ADMIN_IS_ACTIVE', 'is_active');
define('COL_ADMIN_NAME', 'name');
define('COL_ADMIN_USERNAME', 'username');
define('COL_ADMIN_PASSWORD', 'password');
define('COL_ADMIN_CREATED_AT', 'created_at');
define('COL_ADMIN_IMAGE', 'image');


define('TBL_USERS', 'customers');
define('COL_USER_ID', 'id');
define('COL_USER_CODE', 'code');
define('COL_USER_PHONENUMBER', 'phonenumber');
define('COL_USER_PASSWORD', 'password');
define('COL_USER_EMAIL', 'email');
define('COL_USER_FIRST_NAME', 'first_name');
define('COL_USER_PROFILE_IMAGE', 'profile_image');
define('COL_USER_STATUS', 'status');
define('COL_USER_IP', 'ip');
define('COL_USER_LAST_ACTIVITY', 'last_activity');
define('COL_USER_DEVICE_INFO', 'device_info');
define('COL_USER_FIREBASE_TOKEN', 'firebase_token');
define('COL_USER_APP_VERSION', 'app_version');
define('COL_USER_CREATED_AT', 'created_at');
define('COL_USER_IS_SEEN', 'is_seen');
define('COL_USER_OS', 'os');
define('COL_USER_COMMENT', 'comment');


define('TBL_SETTINGS', 'settings');
define('COL_SETTING_ID', 'id');
define('COL_SETTING_NAME', 'name');
define('COL_SETTING_VALUE', 'value');

define('TBL_ROLES', 'roles');
define('COL_ROLE_ID', 'id');
define('COL_ROLE_NAME', 'name');
define('COL_ROLE_DESC', 'desc');


define('COL_CREATED_AT', 'created_at');
define('COL_UPDATED_AT', 'updated_at');


// -------------------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------        ENUMS       ---------------------------------------------------------------
// -------------------------------------------------------------------------------------------------------------------------------------



define('ENUM_USER_STATUS_PENDING', 'pending');
define('ENUM_USER_STATUS_WAIT_FOR_CHECK', 'wait_for_check');
define('ENUM_USER_STATUS_VALIDATED', 'validated');
define('ENUM_USER_STATUS_DEFECTED', 'defected');

// ------------------------------------------------------------------------------------------------------------------------------------
// --------------------------------------------------     ADMIN PERMISSIONS      ------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------------

define('PERM_ROOT', 'root');
define('PERM_ADMIN', 'admin');
define('PERM_SETTING', 'setting');
define('PERM_USER', 'user');

define('PERM_ROLE_LIST_SHOW', 'PERM_ROLE_LIST_SHOW');
define('PERM_ROLE_STORE', 'PERM_ROLE_STORE');
define('PERM_ROLE_UPDATE', 'PERM_ROLE_UPDATE');
define('PERM_ROLE_DESTROY', 'PERM_ROLE_DESTROY');
define('PERM_ROLE_PERMISSION', 'PERM_ROLE_PERMISSION');


define('PERM_ADMIN_LIST_SHOW', 'PERM_ADMIN_LIST_SHOW');
define('PERM_ADMIN_STORE', 'PERM_ADMIN_STORE');
define('PERM_ADMIN_UPDATE', 'PERM_ADMIN_UPDATE');
define('PERM_ADMIN_DESTROY', 'PERM_ADMIN_DESTROY');
define('PERM_ADMIN_ROLE', 'PERM_ADMIN_ROLE');

