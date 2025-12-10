<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Controllers\CMS\BaseCMSController;
use App\Models\AuthUser;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseCMSController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function authenticate(Request $request)
    {
        $username = $request->username ?? null;
        $password = $request->password ?? null;
        $captcha = $request->captcha ?? null;

        if (empty($username))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memasukkan Username"
            ]);
        }
        if (empty($password))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memasukkan Password"
            ]);
        }

        // hash
        $password = md5($password);

        // cek captcha
        $input_captcha = $captcha;
        $stored_captcha = session('captcha_code');

        if (!$stored_captcha || strcmp($input_captcha, $stored_captcha) !== 0)
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Silahkan Masukkan Captcha dengan Benar"
            ]);
        }

        // hapus captcha biar nggak bisa reuse
        session()->forget('captcha_code');
        // 

        // cek user
        $qGetDataUser = User::select("userid", "username")
            ->where("userid", $username)
            ->where("password", $password);
        $nGetDataUser = $qGetDataUser->count();
        $fGetDataUser = $qGetDataUser->first();
        if ($nGetDataUser <= 0)
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Silahkan Masukkan Akun Anda dengan Benar",
                "refresh_captcha" => 1
            ]);
        }

        // Pastikan yang login mhs, admin, kemahasiswaan
        $allowed_role = ["mhs", "admin", "kemhsan"];
        // Get Default Role & Unit
        $qGetDefaultRole = UserRole::select("idrole", "kodeunit")
            ->where("userid", $fGetDataUser->userid)
            ->whereIn("idrole", $allowed_role)
            ->where("isdefault", "1");
        $nGetDefaultRole = $qGetDefaultRole->count();
        $fGetDefaultRole = $qGetDefaultRole->first();
        if ($nGetDefaultRole >= 1)
        {
            if (!in_array($fGetDefaultRole->idrole, $allowed_role))
            {
                return response()->json([
                    "msg_resp" => "error",
                    "msg_desc" => "Silahkan Masukkan Akun Anda dengan Benar",
                    "refresh_captcha" => 1
                ]);
            }
            else
            {
                $idrole = $fGetDefaultRole->idrole;
            }
        }
        else
        {
            // user belum punya default role ambil role yang pertama
            $qGetFirstRole = UserRole::select("idrole", "kodeunit")
                ->where("userid", $fGetDataUser->userid)
                ->whereIn("idrole", $allowed_role);
            $nGetFirstRole = $qGetFirstRole->count();
            $fGetFirstRole = $qGetFirstRole->first();
            if ($nGetFirstRole <= 0 || !in_array($fGetFirstRole->idrole, $allowed_role))
            {
                return response()->json([
                    "msg_resp" => "error",
                    "msg_desc" => "Silahkan Masukkan Akun Anda dengan Benar",
                    "refresh_captcha" => 1
                ]);
            }
            else
            {
                $idrole = $fGetFirstRole->idrole;
            }
        }

        if (empty($idrole))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Silahkan Masukkan Akun Anda dengan Benar",
                "refresh_captcha" => 1
            ]);
        }

        if (in_array($idrole, ["mhs"]))
        {
            // Cek di ms_mhs
            $qCekTabelMhs = Mahasiswa::select("nim", "statusmhs")
                ->where("statusmhs", "A")
                ->where("program", "0")
                ->where("nim", $fGetDataUser->userid);
            $nCekTabelMhs = $qCekTabelMhs->count();
            // $fCekTabelMhs = $qCekTabelMhs->first();
            if ($nCekTabelMhs <= 0)
            {
                return response()->json([
                    "msg_resp" => "error",
                    "msg_desc" => "Silahkan Masukkan Akun Anda dengan Benar",
                    "refresh_captcha" => 1
                ]);
            }
        }

        if ($idrole == "kemhsan")
        {
            $idrole = "admin";
        }

        if ($idrole == self::$GLO_TIPEUSER_ADMIN)
        {
            $auth_guard = "web";
        }
        else if ($idrole == self::$GLO_TIPEUSER_MAHASISWA)
        {
            $auth_guard = "mahasiswa";
        }
        else
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Silahkan Masukkan Akun Anda dengan Benar",
                "refresh_captcha" => 1
            ]);
        }

        // attempt login
        $authenticatedUser = AuthUser::where("userid", $fGetDataUser->userid)->first();
        Auth::guard($auth_guard)->login($authenticatedUser);

        if (Auth::guard($auth_guard)->check())
        {
            $request->session()->regenerate();

            session()->put('originaluser_id', $authenticatedUser->userid);
            session()->put('originaluser_username', $authenticatedUser->userid);
            session()->put('originaluser_name', $authenticatedUser->username);
            session()->put('originaluser_tipe', $idrole);

            $tipeUser = $idrole;
            $this->defaultRouteUser = Helper::defaultRouteUser($tipeUser, $authenticatedUser->userid);

            $redirect = $this->defaultRouteUser;

            if (!empty($request->redirect))
            {
                if (in_array($request->redirect, ['']))
                {
                    $redirect = $request->redirect;
                }
            }

            return response()->json([
                "msg_resp" => "success",
                "msg_desc" => "",
                "redirect" => $redirect
            ]);
        }
        else
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Silahkan Masukkan Akun Anda dengan Benar",
                "refresh_captcha" => 1
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
