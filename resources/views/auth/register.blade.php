<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrasi - SIMGANG</title>
    <link rel="icon" href="{{ asset('images/logo-kominfo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    {!! NoCaptcha::renderJs() !!}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f7ff;
            margin: 0;
            padding: 20px 0;
        }
        
        .login-container {
            display: flex;
            width: 90%;
            max-width: 1100px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            min-height: 600px;
        }
        
        .left-box {
            width: 50%;
            background: linear-gradient(135deg, #4568dc, #6157ff);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .left-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect fill="none" width="100" height="100"/><path fill="rgba(255,255,255,0.03)" d="M0 0L100 0 100 100 0 100Z"/></svg>');
            opacity: 0.1;
        }
        
        .left-box h2 {
            margin-bottom: 24px;
            font-size: 28px;
            font-weight: 600;
            position: relative;
        }
        
        .left-box h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 3px;
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 2px;
        }
        
        .left-box p {
            font-size: 15px;
            line-height: 1.6;
            font-weight: 300;
            margin-bottom: 30px;
            max-width: 85%;
        }
        
        .stats-container {
            width: 100%;
            margin-top: 20px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .stat-box {
            padding: 15px;
            text-align: center;
        }
        
        .stat-box i {
            font-size: 28px;
            margin-bottom: 8px;
            opacity: 0.9;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            margin: 8px 0;
        }
        
        .stat-label {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .form-box-container {
            width: 50%;
            padding: 30px 40px 40px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            background-color: #ffffff;
            overflow-y: auto;
            max-height: 90vh;
        }
        
        .logo {
            width: 110px;
            height: auto;
            margin: 15px auto 20px;
            display: block;
            transition: transform 0.3s ease;
            object-fit: contain;
        }
        
        .logo:hover {
            transform: scale(1.05);
        }
        
        .form-box-container h2 {
            font-size: 26px;
            margin-bottom: 25px;
            color: #333;
            font-weight: 600;
        }
        
        .alert {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-success {
            background: #d4f4dd;
            color: #2d7a4a;
            border-left: 4px solid #2d7a4a;
        }
        
        .alert-error {
            background: #fdd4d4;
            color: #7a2d2d;
            border-left: 4px solid #7a2d2d;
        }
        
        .input-box {
            width: 100%;
            margin-bottom: 18px;
        }
        
        .input-box label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }
        
        .input-box input, 
        .input-box select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e1e5ee;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            color: #333;
            background-color: #f9fafc;
            font-family: 'Poppins', sans-serif;
        }
        
        .input-box input:focus, 
        .input-box select:focus {
            outline: none;
            border-color: #4568dc;
            box-shadow: 0 0 0 3px rgba(69, 104, 220, 0.1);
            background-color: #fff;
        }
        
        .input-box input::placeholder {
            color: #aab2c8;
        }
        
        .input-box small {
            display: block;
            margin-top: 4px;
            font-size: 11px;
            color: #718096;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4568dc, #6157ff);
            color: white;
            padding: 13px 28px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 15px;
            font-weight: 500;
            letter-spacing: 0.5px;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 4px 15px rgba(69, 104, 220, 0.2);
            font-family: 'Poppins', sans-serif;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #3755c7, #5048e5);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(69, 104, 220, 0.3);
        }
        
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(69, 104, 220, 0.2);
        }

        /* CAPTCHA */
        .captcha-box {
            width: 100%;
            margin-bottom: 18px;
        }
        .captcha-box label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }
        .captcha-widget {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .captcha-widget img {
            border-radius: 8px;
            border: 1px solid #e1e5ee;
            height: 50px;
            cursor: pointer;
        }
        .captcha-refresh {
            background: none;
            border: 1px solid #e1e5ee;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 18px;
            color: #4568dc;
            transition: all 0.2s;
            line-height: 1;
        }
        .captcha-refresh:hover {
            background: rgba(69,104,220,0.08);
            border-color: #4568dc;
        }
        .captcha-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e1e5ee;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            color: #333;
            background-color: #f9fafc;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .captcha-input:focus {
            outline: none;
            border-color: #4568dc;
            box-shadow: 0 0 0 3px rgba(69, 104, 220, 0.1);
            background-color: #fff;
        }
        .captcha-error {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }
        
        .register-link {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
            text-align: center;
        }
        
        .register-link a {
            color: #4568dc;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .register-link a:hover {
            color: #3755c7;
            text-decoration: underline;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                min-height: auto;
            }
            .left-box, .form-box-container {
                width: 100%;
                padding: 30px;
            }
            .left-box {
                padding-top: 40px;
                padding-bottom: 40px;
            }
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .login-container {
                width: 95%;
                border-radius: 15px;
            }
            .left-box, .form-box-container {
                padding: 25px 20px;
            }
            .left-box h2 {
                font-size: 22px;
            }
            .form-box-container h2 {
                font-size: 22px;
                margin-bottom: 20px;
            }
        }

        /* Password Visibility Toggle */
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        .password-wrapper input {
            padding-right: 40px !important;
        }
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            transition: color 0.2s;
            z-index: 10;
        }
        .toggle-password:hover {
            color: #4b5563;
        }
        /* Hide default browser password reveal eye icon */
        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
    </style>
    <!-- SweetAlert2 untuk notifikasi yang lebih baik -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Helper Notifikasi -->
    <script src="{{ asset('js/notif-helper.js') }}"></script>
</head>
<body>
    <div class="login-container">
        <div class="left-box">
            <h2>Selamat Datang di SIMGANG</h2>
            <p>Sistem Informasi Magang Diskominfo Cilacap</p>
            <p style="font-size: 14px;">Bergabunglah dengan ribuan peserta yang telah mengikuti program magang di Diskominfo Cilacap</p>
            
            <div class="stats-container">
                <div class="stat-box">
                    <div class="stat-number">100+</div>
                    <div class="stat-label">Peserta Magang</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">20+</div>
                    <div class="stat-label">Institusi Pendidikan</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Bidang Magang</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Tingkat Sukses</div>
                </div>
            </div>
        </div>
        
        <div class="form-box-container">
            <img src="{{ asset('images/logo-kominfo.png') }}" alt="Logo Kominfo" class="logo">
            <h2>Daftar Akun</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <strong>✓ Sukses:</strong> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    <strong>✗ Error:</strong> {{ session('error') }}
                </div>
            @endif
            
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>✗ Validasi Error:</strong>
                    <ul style="margin: 8px 0 0 20px; padding: 0;">
                        @foreach ($errors->all() as $error)
                            <li style="margin: 4px 0;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" id="register-form" action="{{ route('register.process') }}">
                @csrf
                
                <div class="input-box" style="margin-bottom: 20px;">
                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        padding: 10px 14px;
                        background: linear-gradient(135deg, rgba(69,104,220,0.08), rgba(97,87,255,0.08));
                        border: 1px solid rgba(69,104,220,0.2);
                        border-radius: 10px;
                        font-size: 13px;
                        color: #4568dc;
                        font-weight: 500;
                    ">
                        <span style="font-size: 18px;">🎓</span>
                        <div style="line-height: 1.4;">
                            <div>Mendaftar sebagai <strong>Siswa / Mahasiswa</strong></div>
                            <div style="font-size: 11px; color: #718096; font-weight: 400; margin-top: 1px;">Akun Mentor & Sekretariat dibuat oleh admin</div>
                        </div>
                    </div>
                </div>
                
                <div class="input-box">
                    <label for="nama">Nama Lengkap <span style="color: #e53e3e;">*</span></label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}" required />
                    <small>Minimal 3 karakter</small>
                </div>
                
                <div class="input-box">
                    <label for="email">Email <span style="color: #e53e3e;">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" value="{{ old('email') }}" required />
                    <small>Contoh: nama@email.com</small>
                </div>
                
                <div class="input-box">
                    <label for="password">Password <span style="color: #e53e3e;">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Buat password" autocomplete="new-password" required />
                        <i class="fa-solid fa-eye-slash toggle-password" id="toggle-password-icon"></i>
                    </div>
                    <small style="color: #4568dc; font-weight: 500; display: block; margin-top: 4px;">
                        ℹ️ Wajib kombinasi huruf, angka, dan karakter khusus/simbol (minimal 8 digit)
                    </small>
                </div>
                
                <div class="input-box">
                    <label for="password_confirmation">Konfirmasi Password <span style="color: #e53e3e;">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" autocomplete="new-password" required />
                        <i class="fa-solid fa-eye-slash toggle-password" id="toggle-password-confirm-icon"></i>
                    </div>
                </div>
                
                <div class="input-box" style="display: flex; justify-content: center; margin-bottom: 16px;">
                    {!! NoCaptcha::display() !!}
                </div>

                {{-- Image CAPTCHA --}}
                <div class="captcha-box">
                    <label>Kode Verifikasi <span style="color: #e53e3e;">*</span></label>
                    <div class="captcha-widget">
                        <img id="captcha-img"
                             src="{{ route('captcha.generate') }}"
                             alt="CAPTCHA"
                             title="Klik untuk ganti kode"
                             onclick="refreshCaptcha()"
                             style="height:50px; min-width:130px;"
                        />
                        <button type="button" class="captcha-refresh" onclick="refreshCaptcha()" title="Ganti kode">&#x21BA;</button>
                    </div>
                    <input type="text"
                           id="captcha_input"
                           name="captcha_input"
                           class="captcha-input"
                           placeholder="Ketik kode di atas"
                           autocomplete="off"
                           maxlength="5"
                           value="{{ old('captcha_input') }}"
                    />
                    @error('captcha_input')
                        <span class="captcha-error">✗ {{ $message }}</span>
                    @enderror
                    <small style="color:#718096;font-size:11px;margin-top:4px;display:block;">Tidak peka huruf besar/kecil. Klik gambar atau ↺ untuk ganti kode.</small>
                </div>
                
                <button type="submit" class="btn-primary">Daftar Sekarang</button>
                
                <p class="register-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
                </p>
            </form>
        </div>
    </div>
    
    <script>
        // Validasi form client-side
        document.getElementById('register-form').addEventListener('submit', function(e) {
            const nama = document.getElementById('nama').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;
            
            if (nama.length < 3) {
                e.preventDefault();
                showErrorToast('Nama minimal 3 karakter', 'Gagal');
                return false;
            }
            
            if (!email.includes('@')) {
                e.preventDefault();
                showErrorToast('Format email tidak valid', 'Gagal');
                return false;
            }
            
            const hasLetter = /[a-zA-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSymbol = /[^a-zA-Z0-9]/.test(password);
            
            if (password.length < 8 || !hasLetter || !hasNumber || !hasSymbol) {
                e.preventDefault();
                showWarningToast('Password wajib minimal 8 karakter dan berupa kombinasi huruf, angka, serta karakter khusus/simbol.', 'Peringatan');
                return false;
            }
            
            if (password !== passwordConfirm) {
                e.preventDefault();
                showErrorToast('Konfirmasi password tidak cocok', 'Gagal');
                return false;
            }
            
            if (typeof grecaptcha !== 'undefined' && grecaptcha.getResponse().length === 0) {
                e.preventDefault();
                showErrorToast('Silakan centang reCAPTCHA terlebih dahulu', 'Gagal');
                return false;
            }

            const captchaInput = document.getElementById('captcha_input').value.trim();
            if (captchaInput.length < 5) {
                e.preventDefault();
                showErrorToast('Silakan isi kode verifikasi 5 karakter yang ditampilkan pada gambar', 'Gagal');
                return false;
            }
        });

        // Refresh CAPTCHA image without reloading the page
        function refreshCaptcha() {
            const img = document.getElementById('captcha-img');
            img.style.opacity = '0.4';
            img.src = '{{ route('captcha.generate') }}?t=' + Date.now();
            img.onload = function () {
                img.style.opacity = '1';
                document.getElementById('captcha_input').value = '';
                document.getElementById('captcha_input').focus();
            };
        }
        // Toggle password visibility
        const togglePassword = document.getElementById('toggle-password-icon');
        const passwordField = document.getElementById('password');
        if (togglePassword && passwordField) {
            togglePassword.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });
        }

        const togglePasswordConfirm = document.getElementById('toggle-password-confirm-icon');
        const passwordConfirmField = document.getElementById('password_confirmation');
        if (togglePasswordConfirm && passwordConfirmField) {
            togglePasswordConfirm.addEventListener('click', function() {
                const type = passwordConfirmField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirmField.setAttribute('type', type);
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });
        }
    </script>
</body>
</html>
