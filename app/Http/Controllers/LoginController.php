<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Models\User; // Menggunakan model User yang telah disesuaikan
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    
    public function showLoginForm()
    {
        // Jika user sudah login, redirect ke dashboard sesuai role
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

        // Get chart data
        $chartData = $this->getChartData();
        
        // Tambahkan statistik tambahan untuk dashboard
        $totalStudents = array_sum($chartData['data']);
        $totalPartners = count($chartData['labels']);
        
        return view('auth.login', [
            'chartData' => $chartData,
            'totalStudents' => $totalStudents,
            'totalPartners' => $totalPartners
        ]);
    }

    public function login(Request $request)
    {
        // Validasi input - terima email atau username
        $validated = $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ], [
            'email.required' => 'Email atau Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);
        
        $loginField = $request->email;
        
        // Log data request untuk debugging
        Log::info('Login attempt:', ['input' => $loginField]);

        // Cari user berdasarkan email atau username
        $user = User::where(function($query) use ($loginField) {
            $query->where('email', $loginField)
                  ->orWhere('username', $loginField);
        })->first();

        // Validasi user dan password
        if ($user && Hash::check($request->password, $user->pass)) {

            // Autentikasi berhasil - manual login
            Auth::login($user);
            $request->session()->regenerate();

            // Set session id_user
            session(['id_user' => $user->id]);
            
            Log::info('User logged in successfully', ['email' => $user->email, 'username' => $user->username, 'role' => $user->role, 'id' => $user->id]);
            
            // Redirect berdasarkan role
            switch ($user->role) {
                case 'siswa':
                    return redirect()->route('siswa.dashboard')
                        ->with('success', 'Selamat datang, ' . ($user->nama ?? $user->username) . '! Anda login sebagai Siswa.');
                case 'mentor':
                    return redirect()->route('mentor.dashboard')
                        ->with('success', 'Selamat datang, ' . ($user->nama ?? $user->username) . '! Anda login sebagai Mentor.');
                case 'sekretariat':
                    return redirect()->route('sekretariat.dashboard')
                        ->with('success', 'Selamat datang, ' . ($user->nama ?? $user->username) . '! Anda login sebagai Sekretariat.');
                default:
                    return redirect()->route('dashboard')
                        ->with('success', 'Selamat datang, ' . ($user->nama ?? $user->username) . '!');
            }
        } else {
             // Autentikasi gagal
            Log::warning('Login failed for input: ' . $loginField);
            
            return redirect()->route('login')
                ->with('error', 'Email/Username atau password salah.')
                ->withInput(['email' => $loginField]);
        }
    }
    public function keluar(){
        // Log untuk debugging
        Log::info('Logout attempt for user: ' . (Auth::check() ? Auth::user()->username : 'Unknown'));
        
        // Simpan informasi user sebelum logout (untuk keperluan log)
        $username = Auth::check() ? Auth::user()->username : null;
            // Logout user
        Auth::logout();

        // Hapus semua data session
        Session::flush();

        // Invalidate session
        Session::invalidate();

        // Regenerate CSRF token
        Session::regenerateToken();
        // Log successful logout
        if ($username) {
            Log::info('User logged out successfully: ' . $username);
        }

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }

    public function logout(Request $request)
    {
        try {
            // Log untuk debugging
            Log::info('Logout attempt for user: ' . (Auth::check() ? Auth::user()->username : 'Unknown'));
            
            // Simpan informasi user sebelum logout (untuk keperluan log)
            $username = Auth::check() ? Auth::user()->username : null;
            
            // Logout Laravel auth
            Auth::logout();

            // Hapus semua session data
            $request->session()->flush();
            
            // Invalidate session & regenerate token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Log successful logout
            if ($username) {
                Log::info('User logged out successfully: ' . $username);
            }

            // Redirect ke halaman login dengan pesan sukses
            return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');

            
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Logout error: ' . $e->getMessage());
            
            // Force logout dan redirect ke login
            Auth::logout();
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('info', 'Anda telah keluar dari sistem.');
        }
    }

    private function getChartData()
    {
        try {
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
            
            // Jika tidak ada data, berikan data dummy
            if (empty($labels)) {
                $labels = ['Sekolah A', 'Sekolah B', 'Sekolah C'];
                $data = [5, 3, 7];
            }

            return [
                'labels' => $labels,
                'data' => $data,
                'title' => 'Jumlah Siswa Magang per Sekolah',
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching chart data: ' . $e->getMessage());
            
            // Return dummy data jika terjadi error
            return [
                'labels' => ['Sekolah A', 'Sekolah B', 'Sekolah C'],
                'data' => [5, 3, 7],
                'title' => 'Jumlah Siswa Magang per Sekolah',
            ];
        }
    }
    
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }
    
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        
        // Logika untuk mengirim email reset password
        // ...
        
        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }
}