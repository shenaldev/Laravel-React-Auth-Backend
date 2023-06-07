<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetTokens;
use App\Models\User;
use App\Traits\UtilsTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    use UtilsTrait;
    private $TOKEN_EXPIRES_IN = 15;

    /**
     * Send Password Reset Token Email
     * @param Requeset $request [users email]
     */
    public function sendResetMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        //Check Email Have An Account If Not Return Error
        $is_email_in_db = User::where('email', '=', $email)->first();
        if (!$is_email_in_db) {
            return response()->json([
                'message' => 'Email address not found in our records.',
                'errors' => [
                    'email' => 'Email address not found in our records.',
                ],
            ]);
        }

        //Generate Token
        $token = $this->generateStringToken(16);
        /**
         * If Email Already In Database Table Update Code Else Create New One
         */
        $email_in_reset_table = PasswordResetTokens::where('email', '=', $email)->first();
        if ($email_in_reset_table) {
            $email_in_reset_table->token = $token;
            $email_in_reset_table->update();
        } else {
            PasswordResetTokens::create([
                'email' => $email,
                'token' => $token,
            ]);
        }

        try {
            Mail::to($email)->send(new ResetPasswordMail($token));
            return response()->json(['message' => 'Success', 'error' => false], 200);
        } catch (Exception $error) {
            PasswordResetTokens::destroy($email);
            return response()->json(['message' => "Failed to send email", 'error' => true], 500);
        }

        return response()->json(['message' => "Server Error", 'error' => true], 500);
    }

    /**
     * Verify Token That User Enters
     * @param Request $request [email]
     * @param $token
     */
    public function verifyToken(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;
        $email_in_reset_table = PasswordResetTokens::where('email', '=', $email)->first();

        //IF Email Not In The Table password_reset_tokens Return Invalid Token
        if (!$email_in_reset_table) {
            return response()->json(['message' => "Invalid token", 'error' => true], 400);
        }

        //If Token Not Match
        if ($token !== $email_in_reset_table->token) {
            return response()->json(['message' => "Invalid token", 'error' => true], 400);
        }

        //If Token Expired
        $current_timestamp = Carbon::now();
        $updated_at_timestamp = Carbon::parse($email_in_reset_table->updated_at);
        $time_difference = $current_timestamp->diff($updated_at_timestamp);

        if ($time_difference->i >= $this->TOKEN_EXPIRES_IN) {
            return response()->json(['message' => "The token has expired. Please request a new token.", 'error' => true], 400);
        }

        return response()->json(['message' => "success", 'error' => false], 200);
    }

    /**
     * RESET THE USERS PASSWORD
     * @param Request $request [email, token, password]
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|max:20',
        ]);

        $email = $request->email;
        $email_in_reset_table = PasswordResetTokens::where('email', '=', $email)->first();

        //IF Email Not In The Table password_reset_tokens Return Invalid Token
        if (!$email_in_reset_table) {
            return response()->json(['message' => "Invalid token", 'error' => true], 400);
        }

        //If Token Not Match
        if ($request->token !== $email_in_reset_table->token) {
            return response()->json(['message' => "Invalid token", 'error' => true], 400);
        }

        //If Token Expired
        $current_timestamp = Carbon::now();
        $updated_at_timestamp = Carbon::parse($email_in_reset_table->updated_at);
        $time_difference = $current_timestamp->diff($updated_at_timestamp);

        if ($time_difference->i >= $this->TOKEN_EXPIRES_IN) {
            return response()->json(['message' => "The token has expired. Please request a new token.", 'error' => true], 400);
        }

        //IF ALL VALIDATION PASS RESET THE PASSWORD
        $user = User::where('email', '=', $email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        PasswordResetTokens::destroy($email_in_reset_table->id);

        return response()->json(['message' => "Your password has been successfully reset.", 'error' => false], 200);
    }
}
