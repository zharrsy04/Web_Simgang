<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi Email - SIMGANG</title>
    <link rel="icon" href="{{ asset('images/logo-kominfo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #4568dc, #6157ff);
            padding: 20px;
        }

        .verify-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            max-width: 460px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            text-align: center;
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(69,104,220,0.12), rgba(97,87,255,0.12));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-wrapper svg {
            width: 40px;
            height: 40px;
            color: #4568dc;
        }

        h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: #718096;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .email-badge {
            display: inline-block;
            font-size: 13px;
            font-weight: 600;
            color: #4568dc;
            background: rgba(69,104,220,0.1);
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 24px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: left;
        }

        .alert-success { background: #d4f4dd; color: #2d7a4a; border-left: 4px solid #2d7a4a; }
        .alert-error { background: #fdd4d4; color: #7a2d2d; border-left: 4px solid #c53030; }
        .alert-info { background: #e8f4fd; color: #2b6cb0; border-left: 4px solid #3182ce; }

        .otp-input-wrapper {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 24px;
        }

        .otp-input-wrapper input {
            width: 50px;
            height: 58px;
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #f8f9fc;
            color: #2d3748;
            font-family: 'Poppins', sans-serif;
            transition: all 0.2s;
            outline: none;
        }

        .otp-input-wrapper input:focus {
            border-color: #4568dc;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(69,104,220,0.15);
        }

        .otp-input-wrapper input.filled {
            border-color: #4568dc;
            background: linear-gradient(135deg, rgba(69,104,220,0.05), rgba(97,87,255,0.05));
        }

        /* Hidden full-input for form submission */
        #otp-hidden { display: none; }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4568dc, #6157ff);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(69,104,220,0.3);
            margin-bottom: 16px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(69,104,220,0.4);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 16px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            font-size: 12px;
            color: #a0aec0;
        }

        .resend-section {
            margin-top: 4px;
        }

        .resend-btn {
            background: none;
            border: none;
            color: #4568dc;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            padding: 8px 16px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .resend-btn:hover { background: rgba(69,104,220,0.08); }
        .resend-btn:disabled { color: #a0aec0; cursor: not-allowed; background: none; }

        .countdown {
            font-size: 13px;
            color: #718096;
            margin-top: 6px;
        }

        .countdown span { font-weight: 600; color: #4568dc; }

        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 13px;
            color: #718096;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover { color: #4568dc; }

        .expires-info {
            font-size: 12px;
            color: #a0aec0;
            margin-bottom: 16px;
        }

        .expires-info span { color: #e53e3e; font-weight: 600; }
    </style>
</head>
<body>
    <div class="verify-card">
        <div class="icon-wrapper">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#4568dc">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>

        <h2>Verifikasi Email</h2>
        <p class="subtitle">Kami telah mengirimkan kode verifikasi 6 digit ke:</p>
        <div class="email-badge">{{ $email }}</div>

        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">ℹ {{ session('info') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $e) <div>✗ {{ $e }}</div> @endforeach
            </div>
        @endif

        @if($expiresAt && !now()->isAfter($expiresAt))
            <p class="expires-info">Kode berlaku hingga <span id="expire-timer">-</span></p>
        @endif

        <form method="POST" action="{{ route('verify-email.verify') }}" id="otp-form">
            @csrf
            <div class="otp-input-wrapper" id="otp-boxes">
                @for($i = 0; $i < 6; $i++)
                    <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]"
                           autocomplete="one-time-code" data-index="{{ $i }}" />
                @endfor
            </div>
            <input type="hidden" name="otp" id="otp-hidden">
            <button type="submit" class="btn-primary" id="verify-btn">Verifikasi Sekarang</button>
        </form>

        <div class="divider"><span>atau</span></div>

        <div class="resend-section">
            <form method="POST" action="{{ route('verify-email.resend') }}" id="resend-form">
                @csrf
                <button type="submit" class="resend-btn" id="resend-btn" disabled>Kirim Ulang Kode</button>
            </form>
            <p class="countdown" id="countdown-text">
                Kirim ulang tersedia dalam <span id="countdown">{{ $resendCooldown }}</span> detik
            </p>
        </div>

        <a href="{{ route('register') }}" class="back-link">← Kembali ke halaman pendaftaran</a>
    </div>

    <script>
        // --- OTP box logic ---
        const digits = document.querySelectorAll('.otp-digit');
        const hiddenInput = document.getElementById('otp-hidden');
        const form = document.getElementById('otp-form');

        digits.forEach((input, idx) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/\D/g, '').slice(-1);
                if (input.value) {
                    input.classList.add('filled');
                    if (idx < digits.length - 1) digits[idx + 1].focus();
                } else {
                    input.classList.remove('filled');
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && idx > 0) {
                    digits[idx - 1].focus();
                }
                if (e.key === 'ArrowLeft' && idx > 0) digits[idx - 1].focus();
                if (e.key === 'ArrowRight' && idx < digits.length - 1) digits[idx + 1].focus();
            });

            // Support paste
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                pasteData.split('').forEach((char, i) => {
                    if (digits[i]) {
                        digits[i].value = char;
                        digits[i].classList.add('filled');
                    }
                });
                const nextEmpty = Array.from(digits).findIndex(d => !d.value);
                if (nextEmpty > -1) digits[nextEmpty].focus();
                else digits[5].focus();
            });
        });

        form.addEventListener('submit', (e) => {
            const otp = Array.from(digits).map(d => d.value).join('');
            if (otp.length < 6) {
                e.preventDefault();
                alert('Harap isi semua 6 digit kode OTP.');
                return;
            }
            hiddenInput.value = otp;
        });

        // Focus first input
        digits[0].focus();

        // --- Countdown for resend ---
        const resendBtn = document.getElementById('resend-btn');
        const countdownEl = document.getElementById('countdown');
        const countdownText = document.getElementById('countdown-text');
        let countdownVal = {{ $resendCooldown }};

        const timer = setInterval(() => {
            countdownVal--;
            if (countdownEl) countdownEl.textContent = countdownVal;
            if (countdownVal <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                if (countdownText) countdownText.style.display = 'none';
            }
        }, 1000);

        // --- Expire countdown ---
        @if($expiresAt && !now()->isAfter($expiresAt))
        const expiresAt = new Date("{{ $expiresAt->toIso8601String() }}");
        const expireEl = document.getElementById('expire-timer');
        const expireTimer = setInterval(() => {
            const now = new Date();
            const diff = Math.max(0, Math.floor((expiresAt - now) / 1000));
            const m = Math.floor(diff / 60);
            const s = diff % 60;
            expireEl.textContent = `${m}:${String(s).padStart(2, '0')}`;
            if (diff <= 0) clearInterval(expireTimer);
        }, 1000);
        @endif

        // Auto-focus force resend if needed
        @if(session('force_resend'))
        document.addEventListener('DOMContentLoaded', () => {
            resendBtn.disabled = false;
            if (countdownText) countdownText.style.display = 'none';
            clearInterval(timer);
        });
        @endif
    </script>
</body>
</html>
