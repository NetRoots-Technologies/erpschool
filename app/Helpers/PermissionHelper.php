<?php

namespace App\Helpers;

use App\Models\Admin\Employees;
use App\User;
use Config;
use Gate;
use Illuminate\Support\Facades\Auth;

use App\Models\ModelHasRoles;
use App\Models\RoleHasPermission;
use App\Models\Permissions;


/**
 * Class to store the entire group tree
 */
class PermissionHelper
{
    /**
     * Initializer
     */

    public static function getUserPermissions()
    {
        $loggedIn = Auth::user()->id;
        $userRoles = ModelHasRoles::where('model_id', $loggedIn)->pluck('role_id');
        //dd($userRoles);
        $permissions = RoleHasPermission::whereIn('role_id', $userRoles)->pluck('permission_id');
        $permission_names = Permissions::whereIn('id', $permissions)->where('status', 1)->pluck('name');
        return json_decode(json_encode($permission_names), true);

    }

}