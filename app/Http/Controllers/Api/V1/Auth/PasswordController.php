<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetTokens;
use App\Models\User;
use App\Traits\UtilsTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{

    use UtilsTrait;

    public function sendResetMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->only('email');

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
}
