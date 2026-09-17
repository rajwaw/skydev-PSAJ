<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
|
| Login sekarang HANYA menggunakan Auth::attempt() standar Laravel.
| Akun admin/pemilik klinik dibuat lewat seeder (php artisan db:seed),
| BUKAN lewat hardcoded credential di route ini.
|
*/

// Halaman login
Route::get('/login', function () {

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

    if (Auth::attempt($credentials, $remember)) {

        request()->session()->regenerate();

        return redirect()->intended('/');
    }

    return back()
        ->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])
        ->onlyInput('email');

})->middleware('throttle:5,1')->name('login');



/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

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
|
| Semua route AI sekarang dilindungi middleware 'auth'. Sebelumnya route
| ini bisa diakses publik tanpa login sama sekali.
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/ai-clinical-assistant', [AiClinicalAssistantController::class, 'index'])->name('ai-clinical-assistant');
    Route::get('/ai-clinical-assistant/pasien/{id}', [AiClinicalAssistantController::class, 'getSummary'])->name('ai-clinical-assistant.summary');
    Route::get('/ai-clinical-assistant/pasien/{id}/grafik', [AiClinicalAssistantController::class, 'getGrafikData'])->name('ai-clinical-assistant.grafik');
    Route::get('/ai-clinical-assistant/percakapan', [AiClinicalAssistantController::class, 'daftarPercakapan'])->name('ai-clinical-assistant.percakapan.index');
    Route::post('/ai-clinical-assistant/percakapan', [AiClinicalAssistantController::class, 'buatPercakapan'])->name('ai-clinical-assistant.percakapan.store');
    Route::get('/ai-clinical-assistant/percakapan/{id}', [AiClinicalAssistantController::class, 'getPercakapan'])->name('ai-clinical-assistant.percakapan.show');
    Route::post('/ai-clinical-assistant/percakapan/{id}/pesan', [AiClinicalAssistantController::class, 'kirimPesan'])->name('ai-clinical-assistant.percakapan.pesan');
});

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