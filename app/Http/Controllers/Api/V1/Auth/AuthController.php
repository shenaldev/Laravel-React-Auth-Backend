<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Traits\UserRolesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    use UserRolesTrait;

    protected $COOKIE_EXPIRE_TIME = 1 * (60 * 24 * 7);
    protected $AUTH_COOKIE_NAME = "agri_token";

    /**
     * @param Request $request
     * USER Login Function Returns Cookie With Access Token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "The provided credentials do not match our records"], 401);
        }

        //CREATE USER TOKEN WITH ROLES THEN RETURN TOKEN WITH COOKIE
        $roles = $this->getUserRoles($user); //GET USER ROLES
        $token = $user->createToken('authToken', $roles)->plainTextToken;
        $encToken = Crypt::encryptString($token);
        $response = [
            'user' => new UserResource($user),
            'token' => $encToken,
        ];

        $cookie = Cookie::make($this->AUTH_COOKIE_NAME, $encToken, $this->COOKIE_EXPIRE_TIME);

        return response()->json($response, 201)->withCookie($cookie);

    }

    /**
     * @param Request $request
     * User Registration Returns Cookie With Access Token
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:1|max:200|string',
            'email' => 'required|email|min:3|max:200|unique:users,email',
            'password' => 'required|min:8|max:20|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        /**
         * Add User Role A Default Role For Every Registered User
         */
        $role = Role::where('slug', '=', 'user')->first();
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);

        $token = $user->createToken('authToken', [$role->slug])->plainTextToken;
        $encToken = Crypt::encryptString($token);

        $response = [
            'user' => new UserResource($user),
            'token' => $encToken,
        ];

        $cookie = Cookie::make($this->AUTH_COOKIE_NAME, $encToken, $this->COOKIE_EXPIRE_TIME);

        return response()->json($response, 201)->withCookie($cookie);
    }

    /**
     * @param Request $request User Request
     * Logout function delete accessToken and delete tokenCookie
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $tokenCookie = Cookie::forget($this->AUTH_COOKIE_NAME);

        return response()->json(['success' => true], 200)->withCookie($tokenCookie);
    }

}
