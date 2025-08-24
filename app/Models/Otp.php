<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'otp', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public static function generateOtp($email)
    {
        // Delete any existing OTPs for this email
        static::where('email', $email)->delete();

        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        Log::info('Generated OTP Record ', ['email' => $email, 'otp' => $otp]);
        return static::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10), // 10 minutes expiry
        ]);
    }

    public static function verifyOtp($email, $otp)
    {
        $otpRecord = static::where('email', $email)
                          ->where('otp', $otp)
                          ->first();

        if (!$otpRecord || $otpRecord->isExpired() || $otpRecord->used == true) {
            return false;
        }

        // Mark the OTP as used after successful verification
        $otpRecord->used = true;
        $otpRecord->save();
        
        // Delete the OTP after successful verification
        // $otpRecord->delete();
        return true;
    }
}