<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SertifikatPKL;
use App\Models\PengajuanMagang;
use App\Models\Task;
use App\Models\Penilaian;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Tests\TestCase;

class SimgangComprehensiveTest extends TestCase
{
    use WithFaker;

    /**
     * Test all public/landing page routes.
     */
    public function test_public_routes(): void
    {
        // 1. Home
        $response = $this->get('/');
        $response->assertStatus(200);

        // 2. Portfolio
        $response = $this->get('/portfolio');
        $response->assertStatus(200);

        // 3. Contact
        $response = $this->get('/contact');
        $response->assertStatus(200);

        // 4. Riwayat
        $response = $this->get('/riwayat');
        $response->assertStatus(200);

        // 5. Kalender Magang
        $response = $this->get('/kalender-magang');
        $response->assertStatus(200);

        // 6. Login Form
        $response = $this->get('/login');
        $response->assertStatus(200);

        // 7. Register Form
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    /**
     * Test login validation and authentication logic.
     */
    public function test_login_validation_and_failures(): void
    {
        // Fail login
        $response = $this->post('/login', [
            'email' => 'nonexistent_user',
            'password' => 'wrong_password',
        ]);
        $response->assertSessionHas('error');

        // Success login for student
        $tempStudent = User::create([
            'username' => 'temp_siswa_' . time(),
            'email' => 'temp_siswa_' . time() . '@test.com',
            'nama' => 'Temp Siswa Test',
            'pass' => Hash::make('mypassword123'),
            'role' => 'siswa'
        ]);

        $response = $this->post('/login', [
            'email' => $tempStudent->username,
            'password' => 'mypassword123'
        ]);
        $response->assertRedirect('/siswa/dashboard');

        $tempStudent->delete();
    }

    /**
     * Test registration of a new student.
     */
    public function test_student_registration_and_apply(): void
    {
        $uniqueStr = time() . rand(10, 99);
        $username = 'siswa_' . $uniqueStr;
        $email = 'siswa_' . $uniqueStr . '@test.com';

        \NoCaptcha::shouldReceive('verifyResponse')
            ->once()
            ->andReturn(true);

        // Register
        $response = $this->withSession(['captcha_code' => 'ABCDE'])->post('/register', [
            'username' => $username,
            'nama' => 'Siswa Baru ' . $uniqueStr,
            'email' => $email,
            'password' => 'Newpassword123!',
            'password_confirmation' => 'Newpassword123!',
            'role' => 'siswa',
            'captcha_input' => 'ABCDE',
            'g-recaptcha-response' => 'test-token',
        ]);

        $response->assertRedirect('/login');

        // Verify in DB
        $user = User::where('username', $username)->first();
        $this->assertNotNull($user);
        $this->assertEquals('siswa', $user->role);

        // Clean up
        $user->delete();
    }

    /**
     * Test role-based authorization: Siswa sections.
     */
    public function test_student_routes_access(): void
    {
        $uniqueStr = time();
        $tempStudent = User::create([
            'username' => 'temp_siswa_auth_' . $uniqueStr,
            'email' => 'temp_siswa_auth_' . $uniqueStr . '@test.com',
            'nama' => 'Temp Siswa Auth',
            'pass' => Hash::make('password123'),
            'role' => 'siswa'
        ]);

        // Create approved pengajuan magang so status check succeeds
        $pengajuan = PengajuanMagang::create([
            'user_id' => $tempStudent->id,
            'nama' => $tempStudent->nama,
            'nim_nis' => $tempStudent->username,
            'asal_sekolah' => 'SMK Test',
            'jurusan' => 'RPL',
            'tempat_lahir' => 'Cilacap',
            'tgl_lahir' => '2005-01-01',
            'bidang' => 'Bidang Informatika',
            'tanggal_mulai' => Carbon::now()->subDays(5),
            'tanggal_selesai' => Carbon::now()->addDays(25),
            'status' => 'approved'
        ]);

        // Create sertifikat PKL linked to this student with all non-default fields
        $sertifikat = SertifikatPKL::create([
            'created_id' => $tempStudent->id,
            'nama_lengkap' => $tempStudent->nama,
            'nim_nis' => $tempStudent->username,
            'asal_sekolah' => 'SMK Test',
            'jurusan' => 'RPL',
            'tempat_lahir' => 'Cilacap',
            'tgl_lahir' => '2005-01-01',
            'bidang' => 'Bidang Informatika',
            'tgl_mulai' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'tgl_selesai' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'tgl_sertifikat' => Carbon::now()->format('Y-m-d'),
            'sts_diambil' => 0,
            'nm_pembimbing' => 'Pembimbing Test',
            'nik_pembimbing' => '123456789',
            'jabatan_pembimbing' => 'Mentor',
            'nm_kadis' => 'Kadis Test',
            'nip_kadis' => '987654321',
            'jabatan_pegawai' => 'Kepala Dinas',
            'sts_kadis' => 1,
            'aprove_pembimbing' => 1,
            'approve_kadis' => 1,
        ]);

        // Update user with id_sertifikat
        $tempStudent->update(['id_sertifikat' => $sertifikat->id_sertifikat]);

        // Access dashboard without login -> redirect to login
        $response = $this->get('/siswa/dashboard');
        $response->assertRedirect('/login');

        // Access with student login
        $response = $this->actingAs($tempStudent)->get('/siswa/dashboard');
        $response->assertStatus(200);

        // Access profile
        $response = $this->actingAs($tempStudent)->get('/siswa/profil');
        $response->assertStatus(200);

        // Access nilai
        $response = $this->actingAs($tempStudent)->get('/siswa/nilai');
        $response->assertStatus(200);

        // Access tasks
        $response = $this->actingAs($tempStudent)->get('/siswa/tasks');
        $response->assertStatus(200);

        // Access internship application form
        $response = $this->actingAs($tempStudent)->get('/pengajuan/form');
        $response->assertStatus(200);

        // Clean up
        $sertifikat->delete();
        $pengajuan->delete();
        $tempStudent->delete();
    }

    /**
     * Test role-based authorization: Mentor sections.
     */
    public function test_mentor_routes_access(): void
    {
        $tempMentor = User::create([
            'username' => 'temp_mentor_' . time(),
            'email' => 'temp_mentor_' . time() . '@test.com',
            'nama' => 'Temp Mentor',
            'pass' => Hash::make('password123'),
            'role' => 'mentor',
            'bidang' => 'Informatika'
        ]);

        // Attempt student route -> should redirect (using roleProtection middleware) to mentor dashboard
        $response = $this->actingAs($tempMentor)->get('/siswa/dashboard');
        $response->assertRedirect('/mentor/dashboard');

        // Access mentor routes
        $response = $this->actingAs($tempMentor)->get('/mentor/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($tempMentor)->get('/mentor/profile');
        $response->assertStatus(200);

        $response = $this->actingAs($tempMentor)->get('/mentor/kuota');
        $response->assertStatus(200);

        $response = $this->actingAs($tempMentor)->get('/mentor/tasks/daftar-tugas');
        $response->assertStatus(200);

        // Clean up
        $tempMentor->delete();
    }

    /**
     * Test role-based authorization: Sekretariat sections.
     */
    public function test_sekretariat_routes_access(): void
    {
        $tempSekretariat = User::create([
            'username' => 'temp_sek_' . time(),
            'email' => 'temp_sek_' . time() . '@test.com',
            'nama' => 'Temp Sekretariat',
            'pass' => Hash::make('password123'),
            'role' => 'sekretariat'
        ]);

        // Access sekretariat routes
        $response = $this->actingAs($tempSekretariat)->get('/sekretariat/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($tempSekretariat)->get('/sekretariat/antrian-sertifikat');
        $response->assertStatus(200);

        $response = $this->actingAs($tempSekretariat)->get('/sekretariat/siswa');
        $response->assertStatus(200);

        $response = $this->actingAs($tempSekretariat)->get('/sekretariat/mentor');
        $response->assertStatus(302);

        $response = $this->actingAs($tempSekretariat)->get('/sekretariat/sertifikat');
        $response->assertStatus(200);

        $response = $this->actingAs($tempSekretariat)->get('/sekretariat/pengajuan');
        $response->assertStatus(200);

        // Clean up
        $tempSekretariat->delete();
    }
}
