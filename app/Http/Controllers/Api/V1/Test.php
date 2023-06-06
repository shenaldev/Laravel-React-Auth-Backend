<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\UserRolesTrait;
use Illuminate\Http\Request;

class Test extends Controller
{
    //
    use UserRolesTrait;

    public function index(Request $request)
    {
        $user = $request->user();
        $roles = $this->getUserRoles($user);

        return response()->json($roles);
    }

    public function user()
    {
        return response()->json("Hi user");
    }

    public function admin()
    {
        return response()->json('Hi Admin');
    }
}
