<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\SertifikatPKL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // Import the Session facade
use App\Models\PdfDocument; // Import the PdfDocument model
use Illuminate\Support\Facades\Validator; // Import the Validator facade for PdfController
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class PenilaianController extends Controller
{
    /**
     * Menampilkan daftar sertifikat PKL yang terkait dengan pengguna yang masuk.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        // Memeriksa apakah 'id_user' ada di sesi.
        // Asumsi: 'id_user' di sesi adalah ID pengguna yang sedang login,
        // dan 'created_id' di tabel SertifikatPKL merujuk pada ID pengguna yang membuat sertifikat tersebut.
        // if (Session::has('id_user')) { // Baris ini dan blok if/else terkait bisa dihapus jika tidak ada login session
            // $idUser = Session::get('id_user'); // Baris ini bisa dihapus jika tidak ada login session

            // Membangun query untuk sertifikat PKL.
            // Hapus atau komentari baris ini untuk menampilkan SEMUA siswa.
            // $query = SertifikatPKL::where('created_id', $idUser); // Filter berdasarkan created_id

            // Query utama untuk menampilkan semua sertifikat
            $query = SertifikatPKL::query(); // Mengambil semua data

            // Menambahkan kondisi pencarian jika ada parameter 'search' dalam request.
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('no_sertifikat', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('nama_lengkap', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('nim_nis', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('bidang', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('jurusan', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('asal_sekolah', 'LIKE', '%' . $searchTerm . '%');
                });
            }

            // Mengurutkan hasil berdasarkan 'id_sertifikat' secara descending dan melakukan paginasi.
            // Mengubah orderBy dari 'created_id' ke 'id_sertifikat' atau kolom lain yang relevan
            $sertifikats = $query->orderBy('id_sertifikat', 'desc')->paginate(10);

            // Mempertahankan parameter pencarian saat melakukan paginasi.
            $sertifikats->appends($request->query());

            return view('penilaian.index', compact('sertifikats'));
        // } else {
        //     // Mengarahkan ke halaman login jika 'id_user' tidak ada di sesi.
        //     return redirect()->route('login');
        // }
    }

    /**
     * Menampilkan detail nilai untuk sertifikat PKL tertentu.
     *
     * @param int $id ID sertifikat PKL
     * @return \Illuminate\View\View
     */
    public function detailNilai($id)
    {
        // Mencari sertifikat PKL berdasarkan ID, atau menghentikan eksekusi jika tidak ditemukan.
        $sertifikat = SertifikatPKL::findOrFail($id);

        // Mengambil semua penilaian yang terkait dengan sertifikat ini, diurutkan berdasarkan aspek.
        $penilaians = Penilaian::where('id_sertifikat', $id)
            ->orderBy('aspek')
            ->get();

        // Ambil nilai tugas yang sudah dinilai untuk siswa ini
        $gradedTasks = \App\Models\TaskSubmission::whereHas('task', function($query) use ($id) {
                $query->where('sertifikat_pkl_id', $id);
            })
            ->where('status', 'graded')
            ->whereNotNull('grade')
            ->with(['task.assignedByUser', 'student'])
            ->orderBy('graded_at', 'desc')
            ->get();

        return view('penilaian.shownilai', compact('sertifikat', 'penilaians', 'gradedTasks'));
    }

    /**
     * Menampilkan formulir untuk membuat penilaian baru.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // Gunakan Session facade atau Auth check untuk memverifikasi autentikasi
        if (Session::has('id_user') || Auth::check()) {
            $sertifikats = SertifikatPKL::orderBy('nama_lengkap', 'asc')->get();
            return view('penilaian.create', compact('sertifikats'));
        } else {
            // Redirect ke halaman login jika belum terotentikasi
            return redirect()->route('login');
        }
    }

    /**
     * Menyimpan penilaian baru ke database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Melakukan validasi data yang masuk.
            $request->validate([
                'aspek' => 'nullable|array',
                'aspek.*' => 'string|max:255',
                'aspek_manual' => 'nullable|string|max:1000',
                'nilai' => 'required|numeric|min:0|max:100',
                'ket' => 'nullable|string',
                'id_sertifikat' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat'
            ], [
                'id_sertifikat.required' => 'Sertifikat harus dipilih.',
                'id_sertifikat.exists' => 'Sertifikat tidak ditemukan.',
                'aspek.*.max' => 'Aspek penilaian maksimal 255 karakter.',
                'aspek_manual.max' => 'Aspek manual maksimal 1000 karakter.',
                'nilai.required' => 'Nilai harus diisi.',
                'nilai.numeric' => 'Nilai harus berupa angka.',
                'nilai.min' => 'Nilai tidak boleh kurang dari 0.',
                'nilai.max' => 'Nilai tidak boleh lebih dari 100.',
            ]);

            if (!$request->aspek && !$request->aspek_manual) {
                return back()->with('error', 'Silakan pilih atau ketik minimal satu aspek penilaian.')->withInput();
            }

            $aspekList = [];

            if ($request->has('aspek')) {
                if (is_array($request->aspek)) {
                    $aspekList = array_merge($aspekList, $request->aspek);
                } else {
                    // Handle if aspek is sent as string (from modal form)
                    $aspekList[] = $request->aspek;
                }
            }

            if ($request->filled('aspek_manual')) {
                $manuals = array_map('trim', explode(',', $request->aspek_manual));
                foreach ($manuals as $m) {
                    if (!empty($m)) {
                        $aspekList[] = $m;
                    }
                }
            }

            $existingPenilaians = Penilaian::where('id_sertifikat', $request->id_sertifikat)
                ->pluck('aspek')
                ->map(function($item) { return strtolower($item); })
                ->toArray();

            $duplicateAspek = [];
            $newAspekList = [];

            foreach ($aspekList as $aspek) {
                if (in_array(strtolower($aspek), $existingPenilaians)) {
                    $duplicateAspek[] = $aspek;
                } else {
                    $newAspekList[] = $aspek;
                }
            }

            if (count($duplicateAspek) > 0) {
                $duplicates = implode(', ', $duplicateAspek);
                return back()->with('error', "Aspek penilaian berikut sudah ada untuk sertifikat ini: $duplicates")->withInput();
            }

            // Membuat entri penilaian baru di database.
            $penilaian = null;
            foreach ($newAspekList as $aspek) {
                $penilaian = Penilaian::create([
                    'aspek' => $aspek,
                    'nilai' => $request->nilai,
                    'ket' => $request->ket,
                    'is_parent' => null,
                    'id_parent' => null,
                    'id_sertifikat' => $request->id_sertifikat
                ]);
            }

            // Log aktivitas
            \Log::info('Penilaian berhasil ditambahkan', [
                'id_penilaian' => $penilaian->id,
                'id_sertifikat' => $request->id_sertifikat,
                'aspek' => $request->aspek,
                'created_by' => Auth::id() ?? 'guest',
                'user_role' => Auth::user()->role ?? 'guest'
            ]);

            return redirect()->route('siswa.shownilai', $request->id_sertifikat)
                ->with('success', 'Penilaian berhasil ditambahkan');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            \Log::warning('Validasi penilaian gagal', [
                'errors' => $e->errors(),
                'user_id' => Auth::id() ?? 'guest'
            ]);

            // Build specific error message
            $errorMessages = [];
            foreach ($e->errors() as $field => $messages) {
                $errorMessages[] = implode(', ', $messages);
            }
            $specificError = implode('; ', $errorMessages);

            return back()->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi data gagal: ' . $specificError);

        } catch (\Exception $e) {
            // Handle general errors
            \Log::error('Error menyimpan penilaian', [
                'error' => $e->getMessage(),
                'id_sertifikat' => $request->id_sertifikat ?? null,
                'user_id' => Auth::id() ?? 'guest',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman cetak untuk penilaian sertifikat.
     * Catatan: Metode ini tampaknya hanya merender tampilan, bukan menghasilkan PDF.
     * Fungsi ekspor PDF ada di `exportToPDF`.
     *
     * @param int $id_sertifikat ID sertifikat PKL
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function cetak($id_sertifikat)
    {
        // Mengambil penilaian dan sertifikat terkait.
        $penilaians = Penilaian::where('id_sertifikat', $id_sertifikat)->get();
        $sertifikat = SertifikatPKL::find($id_sertifikat);

        // Mengarahkan kembali dengan pesan error jika sertifikat tidak ditemukan.
        if (!$sertifikat) {
            return redirect()->route('penilaian.index')->with('error', 'Data sertifikat tidak ditemukan.');
        }

        $kabid = (object)[
            'nama' => $sertifikat->nama_kabid,
            'nip' => $sertifikat->nip_kabid
        ];

        return view('penilaian.cetak', compact('penilaians', 'sertifikat', 'kabid'));
    }

    /**
     * Menampilkan formulir untuk mengedit penilaian yang sudah ada.
     *
     * @param Penilaian $penilaian Model Penilaian yang akan diedit (Route Model Binding)
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Penilaian $penilaian)
    {
        // Gunakan Session facade untuk mengakses data sesi
        if (Session::has('id_user')) {
            // Lanjutkan jika 'id_user' ada di sesi
            $sertifikats = SertifikatPKL::all();
            return view('penilaian.edit', compact('penilaian', 'sertifikats'));
        } else {
            // Redirect ke halaman login jika 'id_user' tidak ada di sesi
            return redirect()->route('login');
        }
    }

    /**
     * Memperbarui penilaian yang sudah ada di database.
     *
     * @param Request $request
     * @param Penilaian $penilaian Model Penilaian yang akan diperbarui (Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Penilaian $penilaian)
    {
        try {
            // Melakukan validasi data yang masuk.
            $request->validate([
                'aspek' => 'nullable|array',
                'aspek.*' => 'string|max:255',
                'aspek_manual' => 'nullable|string|max:1000',
                'nilai' => 'required|numeric|min:0|max:100',
                'ket' => 'nullable|string',
                'is_parent' => 'nullable|string',
                'id_parent' => 'nullable|string',
                'id_sertifikat' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat'
            ], [
                'nilai.required' => 'Nilai harus diisi.',
                'nilai.numeric' => 'Nilai harus berupa angka.',
                'nilai.min' => 'Nilai tidak boleh kurang dari 0.',
                'nilai.max' => 'Nilai tidak boleh lebih dari 100.',
            ]);

            if (!$request->aspek && !$request->aspek_manual) {
                return back()->with('error', 'Silakan pilih atau ketik minimal satu aspek penilaian.')->withInput();
            }

            $aspekList = [];

            if ($request->has('aspek') && is_array($request->aspek)) {
                $aspekList = array_merge($aspekList, $request->aspek);
            }

            if ($request->filled('aspek_manual')) {
                $manuals = array_map('trim', explode(',', $request->aspek_manual));
                foreach ($manuals as $m) {
                    if (!empty($m)) {
                        $aspekList[] = $m;
                    }
                }
            }

            if (count($aspekList) > 0) {
                $newAspek = $aspekList[0];

                // Periksa apakah aspek sudah ada di sertifikat yang sama, kecuali aspek yang sedang diedit
                $existingPenilaian = Penilaian::where('id_sertifikat', $request->id_sertifikat)
                    ->where('id', '!=', $penilaian->id)
                    ->whereRaw('LOWER(aspek) = ?', [strtolower($newAspek)])
                    ->first();

                if ($existingPenilaian) {
                    return back()->with('error', "Aspek penilaian '$newAspek' sudah ada untuk sertifikat ini.")->withInput();
                }

                // Update the existing assessment with the first aspect in the list
                $penilaian->update([
                    'aspek' => $newAspek,
                    'nilai' => $request->nilai,
                    'ket' => $request->ket,
                    'is_parent' => $request->is_parent,
                    'id_parent' => $request->id_parent,
                    'id_sertifikat' => $request->id_sertifikat
                ]);

                // Create new assessments for any additional aspects
                for ($i = 1; $i < count($aspekList); $i++) {
                    Penilaian::create([
                        'aspek' => $aspekList[$i],
                        'nilai' => $request->nilai,
                        'ket' => $request->ket,
                        'is_parent' => $request->is_parent,
                        'id_parent' => $request->id_parent,
                        'id_sertifikat' => $request->id_sertifikat
                    ]);
                }
            }

            // Log aktivitas
            \Log::info('Penilaian berhasil diperbarui', [
                'id_penilaian' => $penilaian->id,
                'id_sertifikat' => $penilaian->id_sertifikat,
                'aspek' => $penilaian->aspek,
                'updated_by' => Auth::id() ?? 'guest',
                'user_role' => Auth::user()->role ?? 'guest'
            ]);

            // Redirect ke tampilan detail penilaian siswa tersebut
            return redirect()->route('siswa.shownilai', $penilaian->id_sertifikat)
                ->with('success', 'Penilaian berhasil diperbarui');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            \Log::warning('Validasi update penilaian gagal', [
                'errors' => $e->errors(),
                'id_penilaian' => $penilaian->id,
                'user_id' => Auth::id() ?? 'guest'
            ]);
            
            return back()->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Validasi data gagal. Periksa kembali input Anda.');

        } catch (\Exception $e) {
            // Handle general errors
            \Log::error('Error memperbarui penilaian', [
                'error' => $e->getMessage(),
                'id_penilaian' => $penilaian->id,
                'user_id' => Auth::id() ?? 'guest',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus penilaian dari database.
     *
     * @param int $id ID penilaian yang akan dihapus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Mencari penilaian berdasarkan ID, atau menghentikan eksekusi jika tidak ditemukan.
            $penilaian = Penilaian::findOrFail($id);
            $id_sertifikat = $penilaian->id_sertifikat; // Menyimpan ID sertifikat untuk redirect

            // Menghapus penilaian.
            $penilaian->delete();


            // Log aktivitas
            \Log::info('Penilaian berhasil dihapus', [
                'id_penilaian' => $id,
                'id_sertifikat' => $id_sertifikat,
                'deleted_by' => Auth::id() ?? 'guest',
                'user_role' => Auth::user()->role ?? 'guest'
            ]);

            // Redirect ke tampilan detail penilaian siswa tersebut
            return redirect()->route('siswa.shownilai', $id_sertifikat)
                ->with('success', 'Aspek penilaian berhasil dihapus.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('Penilaian tidak ditemukan saat penghapusan', [
                'id_penilaian' => $id,
                'user_id' => Auth::id() ?? 'guest'
            ]);

            return back()->with('error', 'Penilaian tidak ditemukan.');

        } catch (\Exception $e) {
            // Handle general errors
            \Log::error('Error menghapus penilaian', [
                'error' => $e->getMessage(),
                'id_penilaian' => $id,
                'user_id' => Auth::id() ?? 'guest',
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menghapus penilaian: ' . $e->getMessage());
        }
    }

    /**
     * Mengekspor sertifikat penilaian ke format PDF.
     * Membutuhkan library 'barryvdh/laravel-dompdf' untuk berfungsi.
     *
     * @param int $id_sertifikat ID sertifikat PKL
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function exportToPDF($id_sertifikat)
    {
        // Mengambil penilaian dan sertifikat terkait.
        $penilaians = Penilaian::where('id_sertifikat', $id_sertifikat)->get();
        $sertifikat = SertifikatPKL::find($id_sertifikat);

        // Mengarahkan kembali dengan pesan error jika sertifikat tidak ditemukan.
        if (!$sertifikat) {
            return redirect()->route('penilaian.index')->with('error', 'Data sertifikat tidak ditemukan.');
        }

        $kabid = (object)[
            'nama' => $sertifikat->nama_kabid,
            'nip' => $sertifikat->nip_kabid
        ];

        // Membuat PDF dari tampilan 'penilaian.cetak'.
        // Asumsi 'dompdf.wrapper' sudah terdaftar sebagai service provider.
        $pdf = app('dompdf.wrapper')->loadView('penilaian.cetak', compact('sertifikat', 'penilaians', 'kabid'));
        $pdf->setPaper('A4', 'landscape')->setOption('margin', 0);

        // Menentukan nama file PDF.
        $fileName = 'sertifikat_' . $sertifikat->nim_nis . '.pdf';

        // Mengunduh file PDF.
        return $pdf->download($fileName);
    }

    /**
     * Menampilkan nilai untuk sertifikat PKL di halaman siswa.
     *
     * @param int $id_sertifikat ID sertifikat PKL
     * @return \Illuminate\View\View
     */
    public function showNilai($id_sertifikat)
    {
        // Mencari sertifikat PKL berdasarkan ID.
        $sertifikat = SertifikatPKL::find($id_sertifikat);

        // Ambil data penilaian terkait sertifikat
        $penilaians = Penilaian::where('id_sertifikat', $id_sertifikat)->get();

        // Cek jika data tidak ditemukan
        if (!$sertifikat || $penilaians->isEmpty()) {
            return view('siswa.nilai')->with('error', 'Data tidak ditemukan.');
        }

        // Kirim data ke view
        return view('siswa.nilai', compact('sertifikat', 'penilaians'));
    }

    public function uploadIndividual(Request $request)
    {
        // 1. Validasi data yang masuk dengan pesan custom
        $validator = Validator::make($request->all(), [
            'id_sertifikat' => [
                'required',
                'exists:tbl_sertifikat_pkl,id_sertifikat',
            ],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'pdf_file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120', // Maksimal 5MB (5 * 1024 KB)
            ],
        ], [
            // Custom error messages
            'id_sertifikat.required' => 'ID Sertifikat harus diisi.',
            'id_sertifikat.exists' => 'ID Sertifikat tidak valid atau tidak ditemukan.',
            'title.required' => 'Judul PDF harus diisi.',
            'title.max' => 'Judul PDF maksimal 255 karakter.',
            'description.max' => 'Deskripsi maksimal 1000 karakter.',
            'pdf_file.required' => 'File PDF harus dipilih.',
            'pdf_file.file' => 'File yang diupload harus berupa file.',
            'pdf_file.mimes' => 'File harus berformat PDF.',
            'pdf_file.max' => 'Ukuran file maksimal 5MB.',
        ]);

        // 2. Mengembalikan respons JSON dengan error jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa input Anda.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            $file = $request->file('pdf_file');
            
            // 3. Cari sertifikat berdasarkan hidden id_sertifikat
            $sertifikat = SertifikatPKL::find($request->id_sertifikat);

            if (!$sertifikat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sertifikat dengan ID: ' . $request->id_sertifikat . ' tidak ditemukan.'
                ], 404); // 404 Not Found
            }

            // --- BAGIAN INI DIHAPUS/DIKOMENTARI ---
            // 4. (DIHAPUS) Cek apakah sudah ada PDF dengan id_sertifikat yang sama
            /*
            $existingPdf = PdfDocument::where('id_sertifikat', $request->id_sertifikat)
                                        ->where('type', 'individual')
                                        ->first();
            
            if ($existingPdf) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF untuk sertifikat ' . $sertifikat->nama_lengkap . ' sudah ada. Hapus yang lama terlebih dahulu atau gunakan fitur update.'
                ], 409); // 409 Conflict
            }
            */
            // --- AKHIR BAGIAN YANG DIHAPUS ---

            // 5. Generate nama file yang aman
            $originalNameWithoutExt = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $safeOriginalName = Str::slug($originalNameWithoutExt);
            
            // Format: timestamp_idSertifikat_sluggedOriginalName.pdf
            $filename = time() . '_' . $sertifikat->id_sertifikat . '_' . $safeOriginalName . '.' . $extension;

            // 6. Simpan file ke storage
            $path = $file->storeAs('pdfs/individual', $filename, 'public');
            
            if (!$path) {
                throw new \Exception('Gagal menyimpan file PDF ke storage.');
            }

            // 7. Simpan informasi file PDF ke database
            $pdfDocument = PdfDocument::create([
                'id_sertifikat' => $request->id_sertifikat,
                'nim_nis' => $sertifikat->nim_nis,
                'title' => $request->title,
                'description' => $request->description,
                'filename' => $filename,
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'uploaded_at' => now(),
                'type' => 'individual'
            ]);

            // 8. Log aktivitas (opsional)
            \Log::info('PDF berhasil diupload', [
                'id_sertifikat' => $request->id_sertifikat,
                'student_name' => $sertifikat->nama_lengkap,
                'filename' => $filename,
                'filesize' => $file->getSize(),
                'uploaded_by' => Auth::id() ?? 'guest'
            ]);

            // 9. Mengembalikan respons JSON sukses dengan data PDF yang diupload
            return response()->json([
                'success' => true,
                'message' => 'File PDF untuk ' . $sertifikat->nama_lengkap . ' berhasil diupload!',
                'data' => [
                    'pdf_url' => Storage::url($path), // URL publik untuk mengakses PDF
                    'pdf_title' => $pdfDocument->title,
                    'pdf_id' => $pdfDocument->id,
                    'student_name' => $sertifikat->nama_lengkap,
                    'id_sertifikat' => $sertifikat->id_sertifikat,
                    'file_size' => number_format($file->getSize() / 1024, 2) . ' KB'
                ]
            ], 200); // 200 OK

        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors
            \Log::error('Database error saat upload PDF: ' . $e->getMessage(), [
                'exception' => $e, 
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database. Silakan coba lagi.'
            ], 500);
            
        } catch (\Exception $e) {
            // Handle general errors
            \Log::error('Gagal mengupload PDF: ' . $e->getMessage(), [
                'exception' => $e, 
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupload file: ' . $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
        
    }
}
