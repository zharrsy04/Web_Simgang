<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Menampilkan form registrasi sekaligus data chart
    public function showRegistrationForm()
    {
        // Jika user sudah login, redirect ke halaman utama sesuai role
        if (Auth::check()) {
            $user = Auth::user();
            switch ($user->role) {
                case 'siswa':
                    return redirect()->route('siswa.dashboard');
                case 'mentor':
                    return redirect()->route('mentor.dashboard');
                case 'sekretariat':
                    return redirect()->route('sekretariat.dashboard');
                default:
                    return redirect()->route('dashboard');
            }
        }

        $chartData = $this->getChartData();
        return view('auth.register', [
            'chartData' => $chartData
        ]);
    }

    // Proses registrasi
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'unique:tbl_login,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[A-Za-z]/', $value)) {
                        $fail('Password harus mengandung minimal satu huruf.');
                    }
                    if (!preg_match('/[0-9]/', $value)) {
                        $fail('Password harus mengandung minimal satu angka.');
                    }
                    if (!preg_match('/[^A-Za-z0-9]/', $value)) {
                        $fail('Password harus mengandung minimal satu karakter khusus/simbol.');
                    }
                },
            ],
            'captcha_input'        => ['required', 'string'],
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'nama.required'                  => 'Nama lengkap harus diisi',
            'nama.min'                       => 'Nama minimal 3 karakter',
            'email.required'                 => 'Email harus diisi',
            'email.email'                    => 'Format email tidak valid',
            'email.unique'                   => 'Email sudah terdaftar. Gunakan email lain.',
            'password.required'              => 'Password harus diisi',
            'password.min'                   => 'Password minimal 8 karakter',
            'password.confirmed'             => 'Konfirmasi password tidak cocok',
            'captcha_input.required'         => 'Kode CAPTCHA harus diisi',
            'g-recaptcha-response.required'  => 'Silakan centang reCAPTCHA terlebih dahulu',
            'g-recaptcha-response.captcha'   => 'Verifikasi reCAPTCHA gagal, silakan coba lagi',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi kode CAPTCHA gambar (case-insensitive)
        $sessionCaptcha = strtolower(session('captcha_code', ''));
        $inputCaptcha   = strtolower(trim($request->captcha_input));
        if (!$sessionCaptcha || $inputCaptcha !== $sessionCaptcha) {
            return redirect()->route('register')
                ->withErrors(['captcha_input' => 'Kode CAPTCHA salah atau sudah kadaluarsa. Silakan coba lagi.'])
                ->withInput();
        }
        // Hapus kode CAPTCHA dari session setelah dipakai
        session()->forget('captcha_code');

        try {
            DB::beginTransaction();

            // Simpan data user — langsung aktif, role dikunci sebagai 'siswa'
            $user = User::create([
                'username'          => explode('@', $request->email)[0],
                'email'             => $request->email,
                'nama'              => $request->nama,
                'pass'              => Hash::make($request->password),
                'role'              => 'siswa',
                'id_sertifikat'     => null,
                'email_verified_at' => now(),
            ]);

            DB::commit();

            Log::info('User registered successfully', [
                'email' => $request->email,
                'id'    => $user->id,
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());

            return redirect()->route('register')
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.')
                ->withInput();
        }
    }


    // Mengambil data untuk chart pendaftaran siswa per sekolah
    private function getChartData()
    {
        $schools = DB::table('tbl_sertifikat_pkl')
            ->groupBy('asal_sekolah')
            ->select('asal_sekolah', DB::raw('count(*) as student_count'))
            ->get();

        $labels = [];
        $data = [];

        foreach ($schools as $school) {
            $labels[] = $school->asal_sekolah;
            $data[] = $school->student_count;
        }

        return [
            'labels' => $labels,
            'data'   => $data,
            'title'  => 'Jumlah Siswa Magang per Sekolah',
        ];
    }
}