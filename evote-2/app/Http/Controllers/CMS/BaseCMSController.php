<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class BaseCMSController extends Controller
{
    public static $GLO_TIPEUSER_ADMIN = 'admin';
    public static $GLO_TIPEUSER_MAHASISWA = 'mhs';

    public static $LBL_TIPEUSER_ADMIN = 'admin';
    public static $LBL_TIPEUSER_MAHASISWA = 'mhs';

    public $authenticatedUser = "";

    public $defaultRouteUser = "";

    public $listFormat = [];

    public $SET_AKSES_TIPEUSER = "";

    public function __construct()
    {
        $this->middleware(function ($request, $next)
        {
            if (Auth::check())
            {
                View::share('GLO_TIPEUSER_ADMIN', self::$GLO_TIPEUSER_ADMIN);
                View::share('GLO_TIPEUSER_MAHASISWA', self::$GLO_TIPEUSER_MAHASISWA);

                View::share('LBL_TIPEUSER_ADMIN', self::$LBL_TIPEUSER_ADMIN);
                View::share('LBL_TIPEUSER_MAHASISWA', self::$LBL_TIPEUSER_MAHASISWA);

                // Default Route TipeUser
                View::share('defaultRouteUser', $this->defaultRouteUser);
                // 

                // User login
                $this->authenticatedUser = Auth::user();

                View::share('authenticatedUser', $this->authenticatedUser);

                // User Role
                $idrole = session('originaluser_tipe');
                View::share('idrole', $idrole);

                if ($idrole == self::$GLO_TIPEUSER_ADMIN)
                {
                    // Nama Hak Akses
                    $this->SET_AKSES_TIPEUSER = self::$LBL_TIPEUSER_ADMIN;
                }
                else if ($idrole == self::$GLO_TIPEUSER_MAHASISWA)
                {
                    // Nama Hak Akses
                    $this->SET_AKSES_TIPEUSER = self::$LBL_TIPEUSER_MAHASISWA;
                }
                View::share('SET_AKSES_TIPEUSER', $this->SET_AKSES_TIPEUSER);

                $currentRouteName = Route::getCurrentRoute()->getName();
                $currentRouteName = Str::before($currentRouteName, '.');
                View::share('currentRouteName', $currentRouteName);

                $currentRoute = Route::has($currentRouteName) ? route($currentRouteName) : null;
                View::share('currentRoute', $currentRoute);
            }
            return $next($request);
            // abort(403);
        });
    }
}
