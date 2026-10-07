<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - SIMGANG</title>
    <link rel="icon" href="{{ asset('images/logo-kominfo.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
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
            height: 100vh;
            background-color: #f5f7ff;
            margin: 0;
        }
        
        .login-container {
            display: flex;
            width: 90%;
            max-width: 1100px;
            min-height: 600px;
            background: #fff;
            border-radius: 20px;
            overflow: visible;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
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
        
        .chart-container {
            width: 100%;
            height: 300px;
            margin-top: 20px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
        }
        
        .form-box-container {
            width: 50%;
            padding: 40px 40px 30px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            background-color: #ffffff;
            overflow-y: auto;
            overflow-x: visible;
        }
        
        .logo {
            width: 100px;
            height: auto;
            max-height: 100px;
            margin: 0 auto 20px;
            display: block;
            transition: transform 0.3s ease;
            object-fit: contain;
            object-position: center;
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
        
        .input-box {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .input-box label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }
        
        .input-box input, 
        .input-box select {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e1e5ee;
            border-radius: 10px;
            font-size: 15px;
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
        
        .btn-primary {
            background: linear-gradient(135deg, #4568dc, #6157ff);
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
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
                height: auto;
                max-height: none;
            }
            .left-box, .form-box-container {
                width: 100%;
                padding: 30px;
            }
            .left-box {
                padding-top: 40px;
                padding-bottom: 40px;
            }
            .chart-container {
                height: 250px;
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
            .chart-container {
                height: 200px;
            }
        }
            .university-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px; /* Jarak antar logo */
            margin-top: 20px; /* Jarak dari kolom login */
            padding: 15px 10px;
            flex-wrap: wrap; /* Agar logo bisa wrap ke baris baru jika terlalu banyak */
        }

        .uni-logo {
            max-width: 45px; /* Ukuran maksimal logo */
            max-height: 45px; /* Batas tinggi logo */
            height: auto;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .uni-logo:hover {
            transform: scale(1.1);
        }
        
        .created-by {
            margin-top: 20px; /* Jarak dari form */
            font-size: 0.85rem;
            color: #777;
            text-align: center;
            font-weight: 500;
        }
        
        .created-by p {
            margin: 0;
            padding: 0;
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

        /* Form Width Control to preserve original size */
        #login-form {
            width: 100%;
            max-width: 320px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="left-box">
            <h2>Selamat Datang di SIMGANG</h2>
            <p>Sistem Informasi Magang (SIMGANG) adalah platform untuk menilai kinerja siswa atau mahasiswa selama magang secara efektif dan efisien. Sistem ini menyediakan alat yang akurat, mudah digunakan, dan membantu pembimbing serta institusi dalam proses evaluasi secara transparan dan terstruktur.</p>
            <div class="chart-container">
                <canvas id="myBarChart"></canvas>
            </div>
        </div>
        <div class="form-box-container">
            <img src="{{ asset('images/logo-kominfo.png') }}" alt="Logo Perusahaan" class="logo">
            <h2>Login</h2>
            
            @if(session('success'))
                <div style="background: #d4f4dd; color: #2d7a4a; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #2d7a4a; width: 100%;">
                    <strong>✓ Sukses:</strong> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div style="background: #fdd4d4; color: #7a2d2d; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #7a2d2d; width: 100%;">
                    <strong>✗ Perhatian:</strong> {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div style="background: #dbeafe; color: #1e40af; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border-left: 4px solid #3b82f6; width: 100%;">
                    <strong>ℹ Info:</strong> {{ session('info') }}
                </div>
            @endif
            
            <form method="POST" id="login-form" action="{{ route('login.process') }}">
                @csrf
                <div class="input-box">
                    <label for="email">Email / Username</label>
                    <input type="text" id="email" name="email" placeholder="Masukkan email atau username Anda" value="{{ old('email') }}" required />
                </div>
                <div class="input-box">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required />
                        <i class="fa-solid fa-eye-slash toggle-password" id="toggle-password-icon"></i>
                    </div>
                    <small style="color: #6b7280; display: block; margin-top: 4px;">
                        ℹ️ Masukkan password kombinasi Anda (huruf, angka, dan simbol, minimal 8 digit)
                    </small>
                </div>
                <button type="submit" class="btn-primary">Login</button>
                <p class="register-link">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                </p>
            </form>
            <!-- Bagian baru untuk keterangan "Dibuat oleh" -->
            <div class="created-by">
                <p>Created by :</p>
            </div>
            <div class="university-logos">
                <!-- Logo universitas -->
                <img src="{{ asset('images/logos/unsoed.png') }}" alt="Logo Universitas Jenderal Soedirman" class="uni-logo">
                <img src="{{ asset('images/logos/UNS.png') }}" alt="Logo Universitas Sebelas Maret" class="uni-logo">
                <img src="{{ asset('images/Logo-PNC.png') }}" alt="Logo Politeknik Negeri Cilacap" class="uni-logo">
                <img src="{{ asset('images/logo unnes.png') }}" alt="Logo Universitas Negeri Semarang" class="uni-logo">
                <img src="{{ asset('images/Logo_Telkom_University_potrait.png') }}" alt="Logo Telkom University" class="uni-logo">
                <img src="{{ asset('images/bsi.png') }}" alt="Logo BSI" class="uni-logo">
                <img src="{{ asset('images/amikom.png') }}" alt="Logo AMIKOM" class="uni-logo">
            </div>
        </div>
    </div>
</body>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myBarChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartData['labels']); ?>,
                datasets: [{
                    label: <?php echo json_encode($chartData['title']); ?>,
                    data: <?php echo json_encode($chartData['data']); ?>,
                    backgroundColor: generateColors(<?php echo count($chartData['labels']); ?>, 0.6),
                    borderColor: generateColors(<?php echo count($chartData['labels']); ?>, 1),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#fff'
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.8)'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });

        function generateColors(count, opacity) {
            const colors = [
                'rgba(255, 255, 255, ' + opacity + ')',  // White
                'rgba(173, 216, 230, ' + opacity + ')',  // Light Blue
                'rgba(135, 206, 250, ' + opacity + ')',  // Sky Blue
                'rgba(176, 224, 230, ' + opacity + ')',  // Powder Blue
                'rgba(173, 216, 230, ' + opacity + ')',  // Light Blue
                'rgba(240, 248, 255, ' + opacity + ')',  // Alice Blue
                'rgba(224, 255, 255, ' + opacity + ')',  // Light Cyan
                'rgba(175, 238, 238, ' + opacity + ')'   // Pale Turquoise
            ];
            let generatedColors = [];
            for (let i = 0; i < count; i++) {
                generatedColors.push(colors[i % colors.length]);
            }
            return generatedColors;
        }
        // Toggle password visibility
        const togglePassword = document.getElementById('toggle-password-icon');
        const passwordField = document.getElementById('password');

        if (togglePassword && passwordField) {
            togglePassword.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                // toggle the eye icon class
                this.classList.toggle('fa-eye-slash');
                this.classList.toggle('fa-eye');
            });
        }
    </script>
</body>
</html>

