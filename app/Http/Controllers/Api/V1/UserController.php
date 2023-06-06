<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();
        return response()->json($user);
    }
}
