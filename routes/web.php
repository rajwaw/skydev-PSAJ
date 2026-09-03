<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AsuhanKeperawatanController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\ImplementasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AiClinicalAssistantController;


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

// Halaman login
Route::get('/login', function () {

    // Kalau sudah login, langsung ke dashboard
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('login');

})->name('login');


// Proses login
Route::post('/login', function () {

    $credentials = request()->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = request()->boolean('remember');

    /*
    |--------------------------------------------------------------------------
    | LOGIN PEMILIK KLINIK
    |--------------------------------------------------------------------------
    |
    | Mendukung akun khusus pemilik klinik (default: yudha@mandalacare.com).
    | Jika berpindah device dan database belum dimigrasi atau akun belum ada,
    | sistem secara otomatis memigrasi tabel dan membuat akun pemilik
    | sehingga tidak perlu migrate / seed manual setiap kali pindah device.
    |
    */

    $ownerEmail = env('OWNER_EMAIL', 'yudha@mandalacare.com');
    $ownerPassword = env('OWNER_PASSWORD', 'password');
    $ownerName = env('OWNER_NAME', 'Yudha Tama');

    // Daftar email yang dikenali sebagai akun pemilik
    $recognizedEmails = array_map('strtolower', array_filter([
        $ownerEmail,
        'yudha@mandalacare.com',
        'admin@mandalacare.com',
    ]));

    // Daftar password cadangan yang dapat digunakan pemilik saat pertama kali setup
    $recognizedPasswords = array_filter([
        $ownerPassword,
        'password',
        'mandalacare123',
        'admin123',
    ]);

    $inputEmail = strtolower(trim($credentials['email']));
    $inputPassword = $credentials['password'];

    try {
        // 1. Auto-Migrate: Cek apakah tabel users sudah ada. Jika belum (misal device baru), jalankan migrasi otomatis
        if (!Schema::hasTable('users')) {
            Artisan::call('migrate', ['--force' => true]);
        }

        // 2. Cek apakah login menggunakan akun khusus Pemilik Klinik
        if (in_array($inputEmail, $recognizedEmails, true) && in_array($inputPassword, $recognizedPasswords, true)) {

            // Auto-create jika akun belum ada di database device ini
            $user = User::firstOrCreate(
                ['email' => $inputEmail],
                [
                    'name' => $ownerName,
                    'password' => Hash::make($inputPassword),
                ]
            );

            // Update password hash jika password yang dimasukkan valid namun berbeda hash lama
            if (!Hash::check($inputPassword, $user->password)) {
                $user->update(['password' => Hash::make($inputPassword)]);
            }

            Auth::login($user, $remember);
            request()->session()->regenerate();

            return redirect()->intended('/');
        }

        // 3. Login autentikasi database standar (untuk akun yang sudah terdaftar dengan password kustom)
        if (Auth::attempt($credentials, $remember)) {

            // Regenerasi session untuk keamanan
            request()->session()->regenerate();

            return redirect()->intended('/');
        }

    } catch (\Throwable $e) {
        return back()
            ->withErrors([
                'email' => 'Gagal menghubungkan ke database. Pastikan database MySQL aktif atau cek konfigurasi .env (' . $e->getMessage() . ')',
            ])
            ->onlyInput('email');
    }

    // Kalau email/password salah
    return back()
        ->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])
        ->onlyInput('email');

})->name('login');



/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Redirect dari /dashboard ke halaman utama (agar link /dashboard tidak 404)
Route::get('/dashboard', function () {
    return redirect()->route('dashboard');
})->middleware('auth');



/*
|--------------------------------------------------------------------------
| PENDAFTARAN PASIEN
|--------------------------------------------------------------------------
*/

Route::get('/pendaftaran', function () {

    return view('pendaftaran');

})->middleware('auth')->name('pendaftaran');


Route::post('/pendaftaran', [PendaftaranController::class, 'store'])
    ->middleware('auth')
    ->name('pendaftaran.store');



/*
|--------------------------------------------------------------------------
| DATA PASIEN
|--------------------------------------------------------------------------
*/

Route::get('/pasien', [PasienController::class, 'index'])
    ->middleware('auth')
    ->name('pasien');

Route::delete('/pasien/{id}', [PasienController::class, 'destroy'])
    ->middleware('auth')
    ->name('pasien.destroy');



/*
|--------------------------------------------------------------------------
| REKAM MEDIS
|--------------------------------------------------------------------------
*/

Route::get('/rekam-medis', [RekamMedisController::class, 'index'])
    ->middleware('auth')
    ->name('rekam-medis');

Route::get('/rekam-medis/pasien/{id}', [RekamMedisController::class, 'getPasienRM'])
    ->middleware('auth')
    ->name('rekam-medis.pasien.detail');



/*
|--------------------------------------------------------------------------
| ASUHAN KEPERAWATAN
|--------------------------------------------------------------------------
*/

Route::get('/asuhan-keperawatan', [AsuhanKeperawatanController::class, 'index'])
    ->middleware('auth')
    ->name('asuhan-keperawatan');

Route::post('/asuhan-keperawatan', [AsuhanKeperawatanController::class, 'store'])
    ->middleware('auth')
    ->name('asuhan-keperawatan.store');

Route::get('/asuhan-keperawatan/pasien/{id}', [AsuhanKeperawatanController::class, 'getPasienDetail'])
    ->middleware('auth')
    ->name('asuhan-keperawatan.pasien.detail');



/*
|--------------------------------------------------------------------------
| EVALUASI
|--------------------------------------------------------------------------
*/

Route::get('/evaluasi', [EvaluasiController::class, 'index'])
    ->middleware('auth')
    ->name('evaluasi');

Route::post('/evaluasi', [EvaluasiController::class, 'store'])
    ->middleware('auth')
    ->name('evaluasi.store');

Route::get('/evaluasi/pasien/{id}', [EvaluasiController::class, 'getPasienDetail'])
    ->middleware('auth')
    ->name('evaluasi.pasien.detail');


/*
|--------------------------------------------------------------------------
| AI CLINICAL ASSISTANT
|--------------------------------------------------------------------------
*/
Route::get('/ai-clinical-assistant', [AiClinicalAssistantController::class, 'index'])->name('ai-clinical-assistant');
Route::get('/ai-clinical-assistant/pasien/{id}', [AiClinicalAssistantController::class, 'getSummary'])->name('ai-clinical-assistant.summary');
Route::get('/ai-clinical-assistant/pasien/{id}/grafik', [AiClinicalAssistantController::class, 'getGrafikData'])->name('ai-clinical-assistant.grafik');
Route::get('/ai-clinical-assistant/percakapan', [AiClinicalAssistantController::class, 'daftarPercakapan'])->name('ai-clinical-assistant.percakapan.index');
Route::post('/ai-clinical-assistant/percakapan', [AiClinicalAssistantController::class, 'buatPercakapan'])->name('ai-clinical-assistant.percakapan.store');
Route::get('/ai-clinical-assistant/percakapan/{id}', [AiClinicalAssistantController::class, 'getPercakapan'])->name('ai-clinical-assistant.percakapan.show');
Route::post('/ai-clinical-assistant/percakapan/{id}/pesan', [AiClinicalAssistantController::class, 'kirimPesan'])->name('ai-clinical-assistant.percakapan.pesan');    

/*
|--------------------------------------------------------------------------
| IMPLEMENTASI
|--------------------------------------------------------------------------
*/

Route::post('/implementasi', [ImplementasiController::class, 'store'])
    ->middleware('auth')
    ->name('implementasi.store');



/*
|--------------------------------------------------------------------------
| PEMBAYARAN
|--------------------------------------------------------------------------
*/

Route::get('/pembayaran', [PembayaranController::class, 'index'])
    ->middleware('auth')
    ->name('pembayaran');

Route::post('/pembayaran', [PembayaranController::class, 'store'])
    ->middleware('auth')
    ->name('pembayaran.store');

Route::get('/pembayaran/pasien/{id}', [PembayaranController::class, 'getPasienDetail'])
    ->middleware('auth')
    ->name('pembayaran.pasien.detail');



/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');

})->name('logout');