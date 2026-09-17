<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifikasi Email SIMGANG</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333333; text-align: center;">Halo, {{ $nama }}!</h2>
        <p style="color: #555555; font-size: 16px; line-height: 1.5;">
            Terima kasih telah mendaftar di SIMGANG (Sistem Informasi Magang Diskominfo Cilacap).
            Untuk menyelesaikan pendaftaran Anda, silakan gunakan kode verifikasi (OTP) berikut:
        </p>
        <div style="text-align: center; margin: 30px 0;">
            <span style="display: inline-block; padding: 15px 30px; font-size: 24px; font-weight: bold; background-color: #f0f4ff; color: #4568dc; border-radius: 8px; letter-spacing: 5px;">
                {{ $otp }}
            </span>
        </div>
        <p style="color: #555555; font-size: 16px; line-height: 1.5;">
            Kode ini akan kadaluarsa dalam <strong>10 menit</strong>. Jika Anda tidak mendaftar di SIMGANG, abaikan email ini.
        </p>
        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">
        <p style="color: #999999; font-size: 12px; text-align: center;">
            &copy; {{ date('Y') }} Diskominfo Kabupaten Cilacap. Semua Hak Dilindungi.
        </p>
    </div>
</body>
</html>
