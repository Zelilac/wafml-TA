<?php

namespace App\Http\Controllers\CMS;

use App\Helpers\Helper;
use App\Http\Controllers\CMS\BaseCMSController;
use App\Models\Hima;
use App\Models\Mahasiswa;
use App\Models\Periode;
use App\Models\PresidenBem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class DashboardController extends BaseCMSController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $filter_periode = request()->get('filter_periode');
        $qGetPeriode = Periode::select(DB::raw("LEFT(periode, 4) AS periode"))
            ->selectRaw("CONCAT(LEFT(periode, 4), '-', LEFT(periode, 4) + 1) AS periode_akademik")
            ->selectRaw("CONCAT(LEFT(periode, 4), '/', LEFT(periode, 4) + 1) AS periode_akademik_2")
            ->where("periode", ">=", "20201")
            ->groupBy(DB::raw("LEFT(periode, 4)"))
            ->orderBy("periode", "asc");
        $fGetPeriode = $qGetPeriode->get();
        View::share('fGetPeriode', $fGetPeriode);
        if (empty($filter_periode))
        {
            $qGetPeriodeAktif = Periode::select(DB::raw("LEFT(periode, 4) AS periode"))
                ->selectRaw("CONCAT(LEFT(periode, 4), '-', LEFT(periode, 4) + 1) AS periode_akademik")
                ->where("aktif_pemilih", "1");
            $fGetPeriodeAktif = $qGetPeriodeAktif->first();

            $filter_periode = $fGetPeriodeAktif->periode_akademik ?? null;
        }
        View::share([
            'filter_periode' => $filter_periode,
        ]);

        $qGetMahasiswa = Mahasiswa::select("nim")
            ->where("statusmhs", "A")
            ->where("program", "0");
        $fGetMahasiswa = $qGetMahasiswa->get();
        View::share([
            "qGetMahasiswa" => $qGetMahasiswa,
            "fGetMahasiswa" => $fGetMahasiswa,
        ]);

        return view('cms/dashboard', []);
    }

    public function chartBarPresidenBem(String $filter_periode)
    {
        $return = [];

        if (empty($filter_periode))
        {
            return response()->json($return);
        }
        $filter_periode = str_replace("-", "/", $filter_periode);

        $qGetPresidenBem = PresidenBem::select("*")
            ->with(["mahasiswa_ketua", "mahasiswa_wakil"])
            ->withCount([
                "votes" => function ($q) use ($filter_periode)
                {
                    $q->where("periode", $filter_periode);
                }
            ])
            ->where("periode", $filter_periode);
        $fGetPresidenBem = $qGetPresidenBem->get();
        if (!$fGetPresidenBem)
        {
            return response()->json($return);
        }

        foreach ($fGetPresidenBem as $index => $item)
        {
            $return[$index]['name'] = optional($item->mahasiswa_ketua)->nama . " | " . optional($item->mahasiswa_wakil)->nama;
            // $return[$index]['name'] = $item->id;
            $return[$index]['jumlah'] = $item->votes_count;
        }

        return response()->json($return);
    }

    public function chartBarHima(String $filter_periode)
    {
        $return = [];

        if (empty($filter_periode))
        {
            return response()->json($return);
        }
        $filter_periode = str_replace("-", "/", $filter_periode);

        $qGetHima = Hima::select("*")
            ->with(["mahasiswa_ketua", "mahasiswa_wakil"])
            ->withCount([
                "votes" => function ($q) use ($filter_periode)
                {
                    $q->where("periode", $filter_periode);
                }
            ])
            ->where("periode", $filter_periode);
        $fGetHima = $qGetHima->get();
        if (!$fGetHima)
        {
            return response()->json($return);
        }

        foreach ($fGetHima as $index => $item)
        {
            $return[$index]['name'] = optional($item->mahasiswa_ketua)->nama . " | " . optional($item->mahasiswa_wakil)->nama;
            // $return[$index]['name'] = $item->id;
            $return[$index]['jumlah'] = $item->votes_count;
        }

        return response()->json($return);
    }
}
