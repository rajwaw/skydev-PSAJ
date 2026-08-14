<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;

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

Route::get('/', function () {

    return view('dashboard');

})->middleware('auth')->name('dashboard');

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

Route::get('/pasien', function () {

    return view('pasien');

})->middleware('auth')->name('pasien');



/*
|--------------------------------------------------------------------------
| REKAM MEDIS
|--------------------------------------------------------------------------
*/

Route::get('/rekam-medis', function () {

    return view('rekam-medis');

})->middleware('auth')->name('rekam-medis');




/*
|--------------------------------------------------------------------------
| ASUHAN KEPERAWATAN
|--------------------------------------------------------------------------
*/

Route::get('/asuhan-keperawatan', function () {

    return view('asuhan-keperawatan');

})->middleware('auth')->name('asuhan-keperawatan');



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