<?php

namespace App\Services;

use App\Models\EmailVerificationCode;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;

class EmailVerificationService
{
    public function __construct(private MailService $mailjet) {}

    public function verify(User $user, string $code): bool
    {
        $verificationRecord = $user->getLatestVerificationCode();

        if (!$verificationRecord) {
            return false;
        }
        if (!$verificationRecord->isValid($code)) {
            return false;
        }


        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            event(new Verified($user));
        }
        $verificationRecord->delete();

        return true;
    }

    public function resend(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }
        $user->emailVerificationCodes()->delete();
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $verificationCode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
        $user->notify(new VerifyEmailNotification($this->mailjet, $verificationCode));
    }
    public function sendVerificationCode(User $user): void
    {
        // حذف الأكواد القديمة
        $user->emailVerificationCodes()->delete();

        // توليد كود جديد
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $verificationCode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
          $this->mailjet->send(
            $user->email,
            'Verify Your Email Address',
            view('emails.verify-email-code', [
                'verificationCode' => $verificationCode,
                'user' => $user,
            ])->render(),
            'Your verification code is: ' . $verificationCode
        );
        
        // بعت الايميل
       // $user->notify(new VerifyEmailNotification($this->mailjet, $verificationCode));
    }
}
