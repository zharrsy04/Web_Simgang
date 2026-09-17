<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail;

class VerificationController extends Controller
{
    // Batas maksimal percobaan verifikasi
    const MAX_ATTEMPTS = 5;
    // Cooldown antar pengiriman ulang (detik)
    const RESEND_COOLDOWN = 60;

    /**
     * Tampilkan halaman input OTP verifikasi email.
     */
    public function show(Request $request)
    {
        $email = session('otp_verify_email');

        if (!$email) {
            return redirect()->route('register')
                ->with('error', 'Sesi verifikasi tidak ditemukan. Silakan daftar ulang.');
        }

        $user = User::where('email', $email)->whereNull('email_verified_at')->first();

        if (!$user) {
            // Mungkin sudah terverifikasi sebelumnya
            $request->session()->forget('otp_verify_email');
            return redirect()->route('login')
                ->with('success', 'Akun Anda sudah aktif. Silakan login.');
        }

        return view('auth.verify', [
            'email'           => $email,
            'resendCooldown'  => self::RESEND_COOLDOWN,
            'expiresAt'       => $user->verification_expires_at,
        ]);
    }

    /**
     * Proses verifikasi OTP yang diinput user.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Kode OTP harus diisi',
            'otp.digits'   => 'Kode OTP harus 6 angka',
        ]);

        $email = session('otp_verify_email');

        if (!$email) {
            return redirect()->route('register')
                ->with('error', 'Sesi verifikasi tidak ditemukan. Silakan daftar ulang.');
        }

        $user = User::where('email', $email)->whereNull('email_verified_at')->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('success', 'Akun Anda sudah aktif. Silakan login.');
        }

        // Cek limit percobaan
        if ($user->verification_attempts >= self::MAX_ATTEMPTS) {
            return redirect()->route('verify-email.show')
                ->with('error', 'Kode OTP sudah tidak valid karena terlalu banyak percobaan salah. Silakan minta kode baru.')
                ->with('force_resend', true);
        }

        // Cek apakah kode sudah kadaluarsa
        if (!$user->verification_expires_at || now()->isAfter($user->verification_expires_at)) {
            return redirect()->route('verify-email.show')
                ->with('error', 'Kode OTP sudah kadaluarsa. Silakan minta kode verifikasi baru.')
                ->with('force_resend', true);
        }

        // Verifikasi kode (hash comparison)
        if (!Hash::check($request->otp, $user->verification_code)) {
            // Tambah jumlah percobaan gagal
            $user->increment('verification_attempts');
            $remaining = self::MAX_ATTEMPTS - $user->verification_attempts;

            Log::warning('OTP verification failed', ['email' => $email, 'attempts' => $user->verification_attempts]);

            if ($remaining <= 0) {
                return redirect()->route('verify-email.show')
                    ->with('error', 'Kode OTP salah. Batas percobaan habis. Silakan minta kode baru.')
                    ->with('force_resend', true);
            }

            return redirect()->route('verify-email.show')
                ->with('error', "Kode OTP salah. Sisa percobaan: {$remaining}.");
        }

        // OTP benar — aktifkan akun
        $user->update([
            'email_verified_at'       => now(),
            'verification_code'       => null,
            'verification_expires_at' => null,
            'verification_attempts'   => 0,
        ]);

        // Bersihkan session verifikasi
        $request->session()->forget('otp_verify_email');

        Log::info('Email verified successfully', ['email' => $email, 'user_id' => $user->id]);

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi! Akun Anda sudah aktif. Silakan login.');
    }

    /**
     * Kirim ulang kode OTP.
     */
    public function resend(Request $request)
    {
        $email = session('otp_verify_email');

        if (!$email) {
            return redirect()->route('register')
                ->with('error', 'Sesi verifikasi tidak ditemukan. Silakan daftar ulang.');
        }

        $user = User::where('email', $email)->whereNull('email_verified_at')->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('info', 'Akun sudah aktif. Silakan login.');
        }

        // Cek cooldown — jangan izinkan kirim ulang jika OTP masih baru saja dibuat
        if ($user->verification_expires_at && now()->isBefore($user->verification_expires_at->subSeconds(600 - self::RESEND_COOLDOWN))) {
            $waitSeconds = now()->diffInSeconds($user->verification_expires_at->subSeconds(600 - self::RESEND_COOLDOWN), false);
            if ($waitSeconds > 0) {
                return redirect()->route('verify-email.show')
                    ->with('error', "Harap tunggu {$waitSeconds} detik sebelum meminta kode baru.");
            }
        }

        // Generate OTP baru
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'verification_code'       => Hash::make($otp),
            'verification_expires_at' => now()->addMinutes(10),
            'verification_attempts'   => 0,
        ]);

        try {
            Mail::to($user->email)->send(new OtpVerificationMail($otp, $user->nama));
            Log::info('OTP resent', ['email' => $email]);
            return redirect()->route('verify-email.show')
                ->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            Log::error('OTP resend failed: ' . $e->getMessage());
            return redirect()->route('verify-email.show')
                ->with('error', 'Gagal mengirim email. Silakan coba lagi beberapa saat.');
        }
    }
}
