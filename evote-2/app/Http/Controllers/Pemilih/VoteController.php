<?php

namespace App\Http\Controllers\Pemilih;

use App\Helpers\Helper;
use App\Http\Controllers\CMS\BaseCMSController;
use App\Models\Hima;
use App\Models\Mahasiswa;
use App\Models\Periode;
use App\Models\PresidenBem;
use App\Models\Vote;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class VoteController extends BaseCMSController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $qGetPeriodeAktif = Periode::select(DB::raw("LEFT(periode, 4) AS periode"))
            ->selectRaw("CONCAT(LEFT(periode, 4), '/', LEFT(periode, 4) + 1) AS periode_akademik")
            ->where("aktif_pemilih", "1");
        $fGetPeriodeAktif = $qGetPeriodeAktif->first();

        $periode_aktif = $fGetPeriodeAktif->periode_akademik ?? null;
        if (!$periode_aktif)
        {
            return;
        }

        $qGetMahasiswa = Mahasiswa::select("nim", "kodeunit")
            ->with("prodi")
            ->where("nim", $this->authenticatedUser->userid);
        $fGetMahasiswa = $qGetMahasiswa->first();
        if (!$fGetMahasiswa)
        {
            return;
        }
        $filter_unit_hima = $fGetMahasiswa->kodeunit;
        if (in_array($fGetMahasiswa->kodeunit, ["1041", "1042", "1043"]))
        {
            $filter_unit_hima = optional($fGetMahasiswa->prodi)->parentunit ?? '';
        }
        if (!$filter_unit_hima)
        {
            return;
        }

        $qGetPresidenBem = PresidenBem::select("*")
            ->where("periode", $periode_aktif);
        $fGetPresidenBem = $qGetPresidenBem->get();
        View::share('fGetPresidenBem', $fGetPresidenBem);

        $qGetHima = Hima::select("*")
            ->where("periode", $periode_aktif)
            ->where("kodeunit", $filter_unit_hima);
        $fGetHima = $qGetHima->get();
        View::share('fGetHima', $fGetHima);

        $qGetVote = Vote::select("*")
            ->where("periode", $periode_aktif)
            ->where("nim", $fGetMahasiswa->nim);
        $fGetVote = $qGetVote->first();
        View::share('fGetVote', $fGetVote);

        return view('pemilih/index', []);
    }

    public function vote(Request $request)
    {
        $qGetPeriodeAktif = Periode::select(DB::raw("LEFT(periode, 4) AS periode"))
            ->selectRaw("CONCAT(LEFT(periode, 4), '/', LEFT(periode, 4) + 1) AS periode_akademik")
            ->where("aktif_pemilih", "1");
        $fGetPeriodeAktif = $qGetPeriodeAktif->first();

        $periode_aktif = $fGetPeriodeAktif->periode_akademik ?? null;
        if (!$periode_aktif)
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => Helper::errorMessage(),
            ]);
        }

        $qGetMahasiswa = Mahasiswa::select("nim", "kodeunit")
            ->with("prodi")
            ->where("nim", $this->authenticatedUser->userid);
        $fGetMahasiswa = $qGetMahasiswa->first();
        if (!$fGetMahasiswa)
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => Helper::errorMessage(),
            ]);
        }

        if (empty($request->id_ms_presiden_bem))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memilih Presiden BEM",
            ]);
        }
        if (empty($request->id_ms_hima))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memilih HIMA",
            ]);
        }

        try
        {
            Vote::updateOrCreate(
                [
                    "periode" => $periode_aktif,
                    "nim" => $fGetMahasiswa->nim,
                ],
                [
                    "id_ms_presiden_bem" => $request->id_ms_presiden_bem,
                    "id_ms_hima" => $request->id_ms_hima,
                    "created_at" => now(),
                ]
            );

            return response()->json([
                "msg_resp" => "success",
                "msg_desc" => ""
            ]);
        }
        catch (QueryException $e)
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => Helper::errorMessage()
            ]);
        }
    }
}
