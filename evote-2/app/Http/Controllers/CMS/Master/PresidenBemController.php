<?php

namespace App\Http\Controllers\CMS\Master;

use App\Helpers\Helper;
use App\Http\Controllers\CMS\BaseCMSController;
use App\Models\Mahasiswa;
use App\Models\Periode;
use App\Models\PresidenBem;
use App\Models\Vote;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class PresidenBemController extends BaseCMSController
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

        if (($canInsert && request()->get('act') == 'tambah') || (($canUpdate || $canRead) && request()->get('act') == 'edit'))
        {
            $filter_periode = request()->get('filter_periode');
            View::share('filter_periode', $filter_periode);

            if (request()->get('act') == 'edit')
            {
                $id_presiden_bem = request()->get('id');
            }
            else
            {
                $id_presiden_bem = '';
            }
            $qGetDataPresidenBem = PresidenBem::select('*')
                ->with(["mahasiswa_ketua", "mahasiswa_wakil"])
                ->where('id', '=', $id_presiden_bem);
            $nGetDataPresidenBem = $qGetDataPresidenBem->count();
            $fGetDataPresidenBem = $qGetDataPresidenBem->first();

            if ($nGetDataPresidenBem <= 0 && request()->get('act') == 'edit')
            {
                return redirect($this->currentRouteName);
            }

            return view('cms.master.presiden-bem', [
                'canRead' => $canRead,
                'canSetAktif' => $canSetAktif,
                'canInsert' => $canInsert,
                'canUpdate' => $canUpdate,
                'canDelete' => $canDelete,
                'nGetDataPresidenBem' => $nGetDataPresidenBem,
                'fGetDataPresidenBem' => $fGetDataPresidenBem,
                'id_presiden_bem' => $id_presiden_bem,
            ]);
        }
        else
        {
            $filter_keyword = request()->get('filter_keyword');
            $filter_periode = request()->get('filter_periode');
            $qGetPeriode = Periode::select(DB::raw("LEFT(periode, 4) AS periode"))
                ->selectRaw("CONCAT(LEFT(periode, 4), '/', LEFT(periode, 4) + 1) AS periode_akademik")
                ->where("periode", ">=", "20201")
                ->groupBy(DB::raw("LEFT(periode, 4)"))
                ->orderBy("periode", "asc");
            $fGetPeriode = $qGetPeriode->get();
            View::share('fGetPeriode', $fGetPeriode);
            if (empty($filter_periode))
            {
                $qGetPeriodeAktif = Periode::select(DB::raw("LEFT(periode, 4) AS periode"))
                    ->selectRaw("CONCAT(LEFT(periode, 4), '/', LEFT(periode, 4) + 1) AS periode_akademik")
                    ->where("aktif_pemilih", "1");
                $fGetPeriodeAktif = $qGetPeriodeAktif->first();

                $filter_periode = $fGetPeriodeAktif->periode_akademik ?? null;
            }
            View::share([
                'filter_keyword' => $filter_keyword,
                'filter_periode' => $filter_periode,
            ]);

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

            $qGetDataPresidenBem = PresidenBem::select("*")
                ->with("mahasiswa_ketua", "mahasiswa_wakil")
                ->orderBy('id', 'asc');
            if (!empty($filter_periode))
            {
                $qGetDataPresidenBem->where('periode', $filter_periode);
            }
            if (!empty($filter_keyword))
            {
                $qGetDataPresidenBem->where(function ($q1) use ($filter_keyword)
                {
                    $q1->whereHas('mahasiswa_ketua', function ($q2) use ($filter_keyword)
                    {
                        $q2->where('nim', 'like', '%' . $filter_keyword . '%')
                            ->orWhere('nama', 'like', '%' . $filter_keyword . '%');
                    });
                    $q1->orWhereHas('mahasiswa_wakil', function ($q2) use ($filter_keyword)
                    {
                        $q2->where('nim', 'like', '%' . $filter_keyword . '%')
                            ->orWhere('nama', 'like', '%' . $filter_keyword . '%');
                    });
                });
            }
            // print_r($qGetDataPresidenBem->toSql());
            // $qGetDataPresidenBem->offset($offset);
            // $qGetDataPresidenBem->limit($limit);
            $fGetDataPresidenBem = $qGetDataPresidenBem->get();

            $qGetDataPresidenBem->getQuery()->offset = null;
            $qGetDataPresidenBem->getQuery()->limit = null;
            $nGetDataPresidenBem = $qGetDataPresidenBem->count();

            $jumlahData = $nGetDataPresidenBem;
            $jumlahHalaman = ceil($jumlahData / $limit);

            return view('cms.master.presiden-bem', [
                'canRead' => $canRead,
                'canSetAktif' => $canSetAktif,
                'canInsert' => $canInsert,
                'canUpdate' => $canUpdate,
                'canDelete' => $canDelete,
                'fGetDataPresidenBem' => $fGetDataPresidenBem,
                'nGetDataPresidenBem' => $nGetDataPresidenBem,
                'jumlahData' => $jumlahData,
                'jumlahHalaman' => $jumlahHalaman,
                'posisiHalaman' => $offset,
            ]);
        }
    }

    public function autocompleteMahasiswa(Request $request)
    {
        $search = $request->get("term");

        if (!$search)
        {
            return response()->json();
        }

        $results = Mahasiswa::select("nim", "nama", "kodeunit")
            ->with("prodi")
            ->where("nama", "LIKE", "%" . $search . "%")
            ->where("statusmhs", "A")
            ->where("program", "0")
            ->limit(10)
            ->get();

        $formatted = [];

        foreach ($results as $row)
        {
            $formatted[] = [
                "nim" => $row->nim,
                "label" => $row->nama,
                "prodi" => optional($row->prodi)->namaunit ?? '',
                "value" => $row->nama
            ];
        }

        return response()->json($formatted);
    }

    public function store(Request $request)
    {
        if (empty($request->periode))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memasukkan Periode"
            ]);
        }
        if (empty($request->nim_ketua))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memasukkan Ketua"
            ]);
        }
        if (!$request->hasFile('foto_ketua'))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memasukkan Foto Ketua"
            ]);
        }
        if ($request->hasFile('foto_ketua') && !in_array($request->file('foto_ketua')->getClientMimeType(), array('image/jpeg', 'image/png')))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Jenis File Foto Ketua Harus JPG atau PNG"
            ]);
        }
        if (empty($request->nim_wakil))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memasukkan Wakil"
            ]);
        }
        if (!$request->hasFile('foto_wakil'))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memasukkan Foto Wakil"
            ]);
        }
        if ($request->hasFile('foto_wakil') && !in_array($request->file('foto_wakil')->getClientMimeType(), array('image/jpeg', 'image/png')))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Jenis File Foto Wakil Harus JPG atau PNG"
            ]);
        }

        $dataSent = $request->only('periode', 'nim_ketua', 'nim_wakil');

        if ($request->hasFile('foto_ketua'))
        {
            $filename_foto_ketua = Helper::getTargetFilename($request->file('foto_ketua')->getClientOriginalName());

            $upload_path = public_path('images/foto');

            $path = $request->file('foto_ketua')->move(
                $upload_path,
                $filename_foto_ketua
            );

            $dataSent["foto_ketua"] = $filename_foto_ketua;
        }

        if ($request->hasFile('foto_wakil'))
        {
            $filename_foto_wakil = Helper::getTargetFilename($request->file('foto_wakil')->getClientOriginalName());

            $upload_path = public_path('images/foto');

            $path = $request->file('foto_wakil')->move(
                $upload_path,
                $filename_foto_wakil
            );

            $dataSent["foto_wakil"] = $filename_foto_wakil;
        }

        $dataSent["created_at"] = now();

        try
        {
            $create = PresidenBem::create($dataSent);

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

    public function update(int $id_presiden_bem, Request $request)
    {
        if (empty($id_presiden_bem))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => Helper::errorMessage()
            ]);
        }
        if (empty($request->nim_ketua))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memasukkan Ketua"
            ]);
        }
        if ($request->hasFile('foto_ketua') && !in_array($request->file('foto_ketua')->getClientMimeType(), array('image/jpeg', 'image/png')))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Jenis File Foto Ketua Harus JPG atau PNG"
            ]);
        }
        if (empty($request->nim_wakil))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memasukkan Wakil"
            ]);
        }
        if ($request->hasFile('foto_wakil') && !in_array($request->file('foto_wakil')->getClientMimeType(), array('image/jpeg', 'image/png')))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Jenis File Foto Wakil Harus JPG atau PNG"
            ]);
        }

        $dataSent = $request->only('nim_ketua', 'nim_wakil');

        if ($request->hasFile('foto_ketua'))
        {
            $filename_foto_ketua = Helper::getTargetFilename($request->file('foto_ketua')->getClientOriginalName());

            $upload_path = public_path('images/foto');

            $path = $request->file('foto_ketua')->move(
                $upload_path,
                $filename_foto_ketua
            );

            $dataSent["foto_ketua"] = $filename_foto_ketua;
        }

        if ($request->hasFile('foto_wakil'))
        {
            $filename_foto_wakil = Helper::getTargetFilename($request->file('foto_wakil')->getClientOriginalName());

            $upload_path = public_path('images/foto');

            $path = $request->file('foto_wakil')->move(
                $upload_path,
                $filename_foto_wakil
            );

            $dataSent["foto_wakil"] = $filename_foto_wakil;
        }

        $getPresidenBem = PresidenBem::where("id", "=", $id_presiden_bem)->first();
        if (!$getPresidenBem)
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => Helper::errorMessage()
            ]);
        }

        try
        {
            $getPresidenBem->update($dataSent);

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

    public function destroy(int $id_presiden_bem)
    {
        if (empty($id_presiden_bem))
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => Helper::errorMessage()
            ]);
        }
        else
        {
            $getPresidenBem = PresidenBem::where("id", "=", $id_presiden_bem)->first();
            if (!$getPresidenBem)
            {
                return response()->json([
                    "msg_resp" => "error",
                    "msg_desc" => Helper::errorMessage()
                ]);
            }

            $countVote = Vote::where("id_ms_presiden_bem", $id_presiden_bem)->count();
            if ($countVote >= 1)
            {
                return response()->json([
                    "msg_resp" => "error",
                    "msg_desc" => Helper::errorMessage(),
                    "constraint" => "1"
                ]);
            }

            $qGetDataPresidenBem = PresidenBem::select('foto_ketua', 'foto_wakil')->where('id', '=', $id_presiden_bem);
            $fGetDataPresidenBem = $qGetDataPresidenBem->first();

            $upload_path = public_path('images/foto');

            if (file_exists($upload_path . '/' . $fGetDataPresidenBem['foto_ketua']))
            {
                unlink($upload_path . '/' . $fGetDataPresidenBem['foto_ketua']);
            }

            if (file_exists($upload_path . '/' . $fGetDataPresidenBem['foto_wakil']))
            {
                unlink($upload_path . '/' . $fGetDataPresidenBem['foto_wakil']);
            }

            try
            {
                $getPresidenBem->delete();

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

    public function getConstraint(int $id)
    {
        if (empty($id))
        {
            return redirect($this->currentRouteName);
        }

        $getPresidenBem = PresidenBem::where("id", "=", $id)->first();
        if (!$getPresidenBem)
        {
            return redirect($this->currentRouteName);
        }

        View::share("id", $id);

        $getVote = Vote::where("id_ms_presiden_bem", $id)->get();
        View::share("getVote", $getVote);

        return view("cms.master.presiden-bem-constraint");
    }

    public function deleteConstraint(int $id, Request $request)
    {
        if (empty($id))
        {
            return redirect($this->currentRouteName);
        }

        $getPresidenBem = PresidenBem::where("id", "=", $id)->first();
        if (!$getPresidenBem)
        {
            return redirect($this->currentRouteName);
        }

        if (count($request->id_vote) <= 0)
        {
            return response()->json([
                "msg_resp" => "error",
                "msg_desc" => "Anda Belum Memilih Data yang Ingin Dihapus",
            ]);
        }

        foreach ($request->id_vote as $id_vote)
        {
            $getVote = Vote::where("id", $id_vote)->where("id_ms_presiden_bem", $id)->first();
            if (!$getVote)
            {
                return response()->json([
                    "msg_resp" => "error",
                    "msg_desc" => Helper::errorMessage(),
                ]);
            }

            try
            {
                $getVote->delete();
            }
            catch (QueryException $e)
            {
                return response()->json([
                    "msg_resp" => "error",
                    "msg_desc" => Helper::errorMessage()
                ]);
            }
        }

        return response()->json([
            "msg_resp" => "success",
            "msg_desc" => ""
        ]);
    }
}
