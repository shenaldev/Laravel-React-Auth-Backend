<?php

namespace App\Http\Controllers\Api\V1\Email;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationMail;
use App\Models\EmailVerification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    //
    private function generateCode()
    {
        $six_digit_random_number = random_int(100000, 999999);
        return $six_digit_random_number;
    }

    /**
     * Send Verification Code To Users Email Address
     * @param $request users email address
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email|min:3|max:200|unique:users,email',
        ]);

        //Check is Email Already In Database (email_verification table)
        $email = $request->email;
        $is_in_db = EmailVerification::where('email', '=', $email)->first();
        $code = $this->generateCode();

        if ($is_in_db) {
            $is_in_db->code = $code;
            $is_in_db->update();
        } else {
            EmailVerification::create([
                'email' => $email,
                'code' => $code,
            ]);
        }

        try {
            Mail::to($email)->send(new EmailVerificationMail($code));
            return response()->json(['message' => 'Success', 'error' => false], 200);
        } catch (Exception $error) {
            return response()->json(['message' => "Failed to send email", 'error' => true], 500);
        }
    }

    /**
     * Validate User Input Code With Database Code
     * @param Request $request
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'code' => 'required|string',
        ]);

        $email = $request->email;
        $code = $request->code;
        $email_in_db = EmailVerification::where('email', '=', $email)->first();

        if ($email_in_db) {
            if ($email_in_db->code == $code) {
                $email_in_db->delete();
                return response()->json(['message' => 'Validaion Success', 'is_valid' => true, 'error' => false], 200);
            }
        }

        return response()->json(['message' => 'Invalid Code', 'is_valid' => false, 'error' => true], 203);
    }
}
