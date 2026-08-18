<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AsuhanKeperawatanController;
use App\Http\Controllers\RekamMedisController;

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
    | Hanya akun yang ada di database yang dapat login.
    | Tidak ada fitur register dari website.
    |
    */

    if (Auth::attempt($credentials, $remember)) {

        // Regenerasi session untuk keamanan
        request()->session()->regenerate();

        return redirect()->intended('/');
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

Route::get('/evaluasi', function () {

    return view('evaluasi');

})->middleware('auth')->name('evaluasi');



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