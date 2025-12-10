<?php

namespace App\Http\Controllers\CMS\Master;

use App\Helpers\Helper;
use App\Http\Controllers\CMS\BaseCMSController;
use App\Models\Periode;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class TahunPeriodeController extends BaseCMSController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // View list dan detail data
        $canRead = in_array($this->SET_AKSES_TIPEUSER, array(self::$LBL_TIPEUSER_ADMIN));

        $canSetAktif = in_array($this->SET_AKSES_TIPEUSER, array(self::$LBL_TIPEUSER_ADMIN));
        $canInsert = in_array($this->SET_AKSES_TIPEUSER, array(self::$LBL_TIPEUSER_ADMIN));
        $canUpdate = in_array($this->SET_AKSES_TIPEUSER, array(self::$LBL_TIPEUSER_ADMIN));
        $canDelete = in_array($this->SET_AKSES_TIPEUSER, array(self::$LBL_TIPEUSER_ADMIN));

        $limit = 10;

        if (empty(request()->get('page_halaman')))
        {
            $page_halaman = 1;
            $offset = 0;
        }
        else
        {
            $page_halaman = request()->get('page_halaman');
            $offset = ($page_halaman - 1) * $limit;
        }

        $qGetDataPeriode = Periode::select(DB::raw("LEFT(periode, 4) AS periode"))
            ->selectRaw("CONCAT(LEFT(periode, 4), '/', LEFT(periode, 4) + 1) AS periode_akademik")
            ->selectRaw("MIN(periode) AS min_periode")
            ->selectRaw("MAX(aktif_pemilih) AS aktif_pemilih")
            ->where("periode", ">=", "20201")
            ->groupBy(DB::raw("LEFT(periode, 4)"))
            ->orderBy("periode", "asc");
        // $qGetDataPeriode->offset($offset);
        // $qGetDataPeriode->limit($limit);
        $fGetDataPeriode = $qGetDataPeriode->get();

        $qGetDataPeriode->getQuery()->offset = null;
        $qGetDataPeriode->getQuery()->limit = null;
        $nGetDataPeriode = $fGetDataPeriode->count();

        $jumlahData = $nGetDataPeriode;
        $jumlahHalaman = ceil($jumlahData / $limit);

        return view('cms.master.tahun-periode', [
            'canRead' => $canRead,
            'canSetAktif' => $canSetAktif,
            'canInsert' => $canInsert,
            'canUpdate' => $canUpdate,
            'canDelete' => $canDelete,
            'fGetDataPeriode' => $fGetDataPeriode,
            'nGetDataPeriode' => $nGetDataPeriode,
            'jumlahData' => $jumlahData,
            'jumlahHalaman' => $jumlahHalaman,
            'posisiHalaman' => $offset,
        ]);
    }

    public function set_aktif(String $periode)
    {
        if (empty($periode))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => Helper::errorMessage()
            ]);
        }

        $dataSent["aktif_pemilih"] = "1";

        $getPeriode = Periode::where("periode", "=", $periode)->first();
        if (!$getPeriode)
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => Helper::errorMessage()
            ]);
        }

        try
        {
            $getPeriode->update($dataSent);

            Periode::where("periode", "!=", $periode)->update([
                "aktif_pemilih" => "0",
            ]);

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
