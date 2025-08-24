<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;

use PHPUnit\TextUI\XmlConfiguration\Logging\Logging;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
        ]);

        // if ($validator->fails()) {
        //     return response_handler('Email already exists or is invalid', false, 422, ['errors' => $validator->errors()->toArray()]);
        // }
        Log::info('Generating OTP Record');

        try {
            $otpRecord = Otp::generateOtp($request->email);
            // Log::info('Generated OTP Record:', $otpRecord->toArray());
            Mail::to($request->email)->send(new OtpMail($otpRecord->otp));
            Log::info("Mail send to ".$request->email);

            return response_handler('OTP sent successfully to your email');
        } catch (\Exception $e) {
            Log::error(''.$e->getMessage());
            return response_handler('Failed to send OTP. Please try again. ' . $e->getMessage(), false, 500);
        }
    }
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response_handler('Invalid input', false, 422, ['errors' => $validator->errors()->toArray()]);
        }

        if (Otp::verifyOtp($request->email, $request->otp)) {
            return response_handler('OTP verified successfully');
        }

        return response_handler('Invalid or expired OTP', false, 400);
    }
}