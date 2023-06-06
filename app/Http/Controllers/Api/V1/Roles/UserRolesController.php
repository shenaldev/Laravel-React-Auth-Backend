<?php

namespace App\Http\Controllers\Api\V1\Roles;

use App\Http\Controllers\Controller;
use App\Models\UserRole;

class UserRolesController extends Controller
{
    /**
     * ADD NEW USER ROLE
     * @param $user_id Users ID Needs To Add
     * @param $role_id Role ID Needs To Add
     */
    public function store($user_id, $role_id)
    {
        //CHECK IF USER ALREADY ASSIGNED BY THE THE ROLE
        $role_exists = UserRole::where('user_id', "=", $user_id)->where('role_id', '=', $role_id)->first();
        if ($role_exists) {
            return response()->json(['message' => 'User Role Already Exists', 'error' => true], 409);
        }

        //ADD NEW ROLE FOR THE USER
        $role = UserRole::create([
            'user_id' => $user_id,
            'role_id' => $role_id,
        ]);

        if ($role) {
            return response()->json(['message' => 'User Role Assigned Success', 'error' => false], 200);
        }

        return response()->json(['message' => 'Server Error', 'error' => true], 500);
    }
}
