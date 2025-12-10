<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\CMS\DashboardController;
use App\Http\Controllers\CMS\Master\HimaController;
use App\Http\Controllers\CMS\Master\PresidenBemController;
use App\Http\Controllers\CMS\Master\TahunPeriodeController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Pemilih\VoteController;
use App\Http\Controllers\WAFExampleController;
use App\Http\Controllers\WAFBlockedController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest:web,mahasiswa')->group(function ()
{
    Route::get('/', [FrontController::class, 'index'])->name('front.home');

    // Route::get('/login', [FrontController::class, 'login'])->name('login.index');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login');

    // Route::get('/privacy-policy', [FrontController::class, 'privacyPolicy'])->name('privacy-policy');

    Route::get('/captcha', [CaptchaController::class, 'image'])->name('captcha.image');
});

// Route::get('/auth/generate-pass', [AuthController::class, 'generate_pass'])->name('auth.generate-pass');

Route::prefix('mahasiswa')->name('mahasiswa.')->middleware('auth:mahasiswa')->group(function ()
{
    Route::controller(AuthController::class)->group(function ()
    {
        Route::get('/logout', 'logout')->name('logout');
    });
    Route::controller(VoteController::class)->group(function ()
    {
        Route::get('/', 'index')->name('index');
        Route::post('/vote', 'vote')->name('vote');
    });
});

Route::middleware('auth:web')->group(function ()
{
    Route::controller(AuthController::class)->group(function ()
    {
        Route::get('/logout', 'logout')->name('logout');
    });
    Route::controller(DashboardController::class)->group(function ()
    {
        Route::get('/dashboard', 'index')->name('dashboard');

        Route::get('/dashboard/chart-bar-presiden-bem/{filter_periode}', 'chartBarPresidenBem')->name('dashboard.chart-bar-presiden-bem');
        Route::get('/dashboard/chart-bar-hima/{filter_periode}', 'chartBarHima')->name('dashboard.chart-bar-hima');
    });
    Route::controller(PresidenBemController::class)->group(function ()
    {
        Route::get('/master-presiden-bem', 'index')->name('master-presiden-bem');

        Route::get('/master-presiden-bem/get-mahasiswa', 'autocompleteMahasiswa')->name('master-presiden-bem.get-mahasiswa');

        Route::get('/master-presiden-bem/get-constraint/{id}', 'getConstraint')->name('master-presiden-bem.get-constraint');
        Route::delete('/master-presiden-bem/delete-constraint/{id}', 'deleteConstraint')->name('master-presiden-bem.delete-constraint');

        Route::post('/master-presiden-bem/add', 'store')->name('master-presiden-bem.store');
        Route::put('/master-presiden-bem/update/{id_presiden_bem}', 'update')->name('master-presiden-bem.update');

        Route::delete('/master-presiden-bem/delete/{id_presiden_bem}', 'destroy')->name('master-presiden-bem.destroy');
    });
    Route::controller(HimaController::class)->group(function ()
    {
        Route::get('/master-hima', 'index')->name('master-hima');

        Route::get('/master-hima/get-mahasiswa/{kodeunit}', 'autocompleteMahasiswa')->name('master-hima.get-mahasiswa');

        Route::get('/master-hima/get-constraint/{id}', 'getConstraint')->name('master-hima.get-constraint');
        Route::delete('/master-hima/delete-constraint/{id}', 'deleteConstraint')->name('master-hima.delete-constraint');

        Route::post('/master-hima/add', 'store')->name('master-hima.store');
        Route::put('/master-hima/update/{id_presiden_bem}', 'update')->name('master-hima.update');

        Route::delete('/master-hima/delete/{id_presiden_bem}', 'destroy')->name('master-hima.destroy');
    });
    Route::controller(TahunPeriodeController::class)->group(function ()
    {
        Route::get('/tahun-periode', 'index')->name('tahun-periode');

        Route::put('/tahun-periode/set/{periode}', 'set_aktif')->name('tahun-periode.set_aktif');
    });
});

// WAF Management Routes
Route::prefix('api/waf')->middleware('auth:web')->group(function () {
    Route::controller(WAFExampleController::class)->group(function () {
        Route::get('/status', 'wafStatus')->name('waf.status');
        Route::post('/reload', 'reloadWAF')->name('waf.reload');
    });
});

// WAF Blocked Page Route (accessible without authentication)
Route::get('/waf/blocked', [WAFBlockedController::class, 'show'])->name('waf.blocked');

