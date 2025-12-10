<?php

namespace App\Helpers;

use App\Http\Controllers\CMS\BaseCMSController;
use Carbon\Carbon;

class Helper extends BaseCMSController
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getBaseURL()
    {
        return "http://localhost:8000/";
    }

    public static function errorMessage()
    {
        // $return = "Something went wrong. Please try again later";
        $return = "Terjadi Kesalahan, Silakan Coba Lagi";

        return $return;
    }

    public static function getNamaHari($nilai)
    {

        if ($nilai == '0')
        {
            return "Senin";
        }
        else if ($nilai == '01' || $nilai == '1')
        {
            return "Selasa";
        }
        else if ($nilai == '02' || $nilai == '2')
        {
            return "Rabu";
        }
        else if ($nilai == '03' || $nilai == '3')
        {
            return "Kamis";
        }
        else if ($nilai == '04' || $nilai == '4')
        {
            return "Jum&#39;at";
        }
        else if ($nilai == '05' || $nilai == '5')
        {
            return "Sabtu";
        }
        else if ($nilai == '06' || $nilai == '6')
        {
            return "Minggu";
        }
    }

    public static function getNamaBulan($nilai)
    {

        if ($nilai == '01' || $nilai == '1')
        {
            return "Januari";
        }
        else if ($nilai == '02' || $nilai == '2')
        {
            return "Februari";
        }
        else if ($nilai == '03' || $nilai == '3')
        {
            return "Maret";
        }
        else if ($nilai == '04' || $nilai == '4')
        {
            return "April";
        }
        else if ($nilai == '05' || $nilai == '5')
        {
            return "Mei";
        }
        else if ($nilai == '06' || $nilai == '6')
        {
            return "Juni";
        }
        else if ($nilai == '07' || $nilai == '7')
        {
            return "Juli";
        }
        else if ($nilai == '08' || $nilai == '8')
        {
            return "Agustus";
        }
        else if ($nilai == '09' || $nilai == '9')
        {
            return "September";
        }
        else if ($nilai == '10')
        {
            return "Oktober";
        }
        else if ($nilai == '11')
        {
            return "Nopember";
        }
        else if ($nilai == '12')
        {
            return "Desember";
        }
    }

    public static function getIndexBulan($nilai)
    {

        if ($nilai == 'Januari')
        {
            return "1";
        }
        else if ($nilai == 'Februari')
        {
            return "2";
        }
        else if ($nilai == 'Maret')
        {
            return "3";
        }
        else if ($nilai == 'April')
        {
            return "4";
        }
        else if ($nilai == 'Mei')
        {
            return "5";
        }
        else if ($nilai == 'Juni')
        {
            return "6";
        }
        else if ($nilai == 'Juli')
        {
            return "7";
        }
        else if ($nilai == 'Agustus')
        {
            return "8";
        }
        else if ($nilai == 'September')
        {
            return "9";
        }
        else if ($nilai == 'Oktober')
        {
            return "10";
        }
        else if ($nilai == 'November')
        {
            return "11";
        }
        else if ($nilai == 'Desember')
        {
            return "12";
        }
    }

    public static function tgl_indo_to_sql($date = "")
    {
        if ($date == '0000-00-00' || empty($date))
        {
            $hasil = "";
        }
        else
        {
            $ex = explode("/", $date);
            $hasil = $ex[2] . "-" . $ex[1] . "-" . $ex[0];
        }

        return $hasil;
    }

    public static function tgl_sql_to_indo($datetime = "", $dengan_jam = "")
    {
        // Pastikan input adalah string
        if (!is_string($datetime) || trim($datetime) === '')
        {
            return null; // Mengembalikan null jika input kosong
        }

        // Cek apakah tanggal adalah 0000-00-00
        if ($datetime === '0000-00-00')
        {
            return null; // Mengembalikan null jika tanggal adalah 0000-00-00
        }

        // Ambil tanggal dari string datetime
        $explode_datetime = explode(' ', $datetime);
        $tanggal = $explode_datetime[0];

        // Ubah format tanggal ke dd/mm/yyyy
        $tanggalFormat = date('d/m/Y', strtotime($tanggal));

        if ($dengan_jam == '1')
        {
            if (isset($explode_datetime[1]))
            {
                $jam = $explode_datetime[1];

                $tanggalFormat .= " " . $jam;
            }
        }

        return $tanggalFormat;
    }

    public static function tglFormatId($date = "")
    {
        if ($date == '0000-00-00' || empty($date))
        {
            $hasil = "";
        }
        else
        {
            $temp = explode("-", $date);
            $tgl = $temp[2];
            $bulan = $temp[1];
            $tahun = $temp[0];
            $hasil = $tgl . " " . self::getNamaBulan($bulan) . " " . $tahun;
        }

        return $hasil;
    }

    public static function revertFormatId($date = "")
    {
        if ($date == '0000-00-00' || empty($date))
        {
            $hasil = "";
        }
        else
        {
            $temp = explode(" ", $date);
            $tgl = $temp[0];
            $bulan = $temp[1];
            $tahun = $temp[2];

            $bulan = self::getIndexBulan($bulan);

            $tgl = str_pad($tgl, 2, '0', STR_PAD_LEFT);
            $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

            $hasil = $tahun . "-" . $bulan . "-" . $tgl;
        }

        return $hasil;
    }

    public static function tglCustomFormat($datetime = "", $from_format = "", $to_format = "")
    {
        // Pastikan input adalah string
        if (!is_string($datetime) || trim($datetime) === '' || trim($from_format) === '' || trim($to_format) === '')
        {
            return null; // Mengembalikan null jika input kosong
        }

        // Cek apakah tanggal adalah 0000-00-00
        if ($datetime === '0000-00-00' || $datetime === '0000-00-00 00:00:00')
        {
            return null; // Mengembalikan null jika tanggal adalah 0000-00-00
        }

        // Ubah format tanggal
        $tanggalFormat = Carbon::createFromFormat($from_format, $datetime)->format($to_format);

        return $tanggalFormat;
    }

    public static function getNamaGelar($gelar)
    {
        $return = '';

        if (in_array(strtolower($gelar), ['d1', 'd2', 'd3', 'd4']))
        {
            $return = 'Diploma';
        }
        else if (in_array(strtolower($gelar), ['s1']))
        {
            $return = 'Sarjana';
        }
        else if (in_array(strtolower($gelar), ['s2']))
        {
            $return = 'Magister';
        }
        else if (in_array(strtolower($gelar), ['s3']))
        {
            $return = 'Doktor';
        }

        return $return;
    }

    public static function getIndex(int $id)
    {

        return ($id - 1);
    }

    public static function returnEmpty(string $string)
    {

        $return = $string;

        if (empty($string))
        {
            $return = '-';
        }

        return $return;
    }

    public static function penyebut(float $nilai)
    {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $return = "";

        if ($nilai < 12)
        {
            $return = " " . $huruf[$nilai];
        }
        else if ($nilai < 20)
        {
            $return = self::penyebut($nilai - 10) . " belas";
        }
        else if ($nilai < 100)
        {
            $return = self::penyebut($nilai / 10) . " puluh" . self::penyebut($nilai % 10);
        }
        else if ($nilai < 200)
        {
            $return = " seratus" . self::penyebut($nilai - 100);
        }
        else if ($nilai < 1000)
        {
            $return = self::penyebut($nilai / 100) . " ratus" . self::penyebut($nilai % 100);
        }
        else if ($nilai < 2000)
        {
            $return = " seribu" . self::penyebut($nilai - 1000);
        }
        else if ($nilai < 1000000)
        {
            $return = self::penyebut($nilai / 1000) . " ribu" . self::penyebut($nilai % 1000);
        }
        else if ($nilai < 1000000000)
        {
            $return = self::penyebut($nilai / 1000000) . " juta" . self::penyebut($nilai % 1000000);
        }
        else if ($nilai < 1000000000000)
        {
            $return = self::penyebut($nilai / 1000000000) . " milyar" . self::penyebut(fmod($nilai, 1000000000));
        }
        else if ($nilai < 1000000000000000)
        {
            $return = self::penyebut($nilai / 1000000000000) . " trilyun" . self::penyebut(fmod($nilai, 1000000000000));
        }
        return $return;
    }

    public static function terbilangAngka(float $nilai, string $suffix = NULL)
    {
        if ($nilai < 0)
        {
            $return = "minus " . trim(self::penyebut($nilai));
        }
        else
        {
            $return = trim(self::penyebut($nilai));
        }

        $return = ucwords($return);

        if ($nilai == '' || $nilai == 0 || is_nan($nilai) || $nilai == null)
        {
            return "-";
        }
        else
        {
            if (!empty($suffix))
            {
                return $return . " $suffix";
            }
            else
            {
                return $return;
            }
        }
    }

    public static function numberToRomanRepresentation(float $number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = "";
        while ($number > 0)
        {
            foreach ($map as $roman => $int)
            {
                if ($number >= $int)
                {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public static function substringOnWordBoundary($string, $start, $length)
    {
        if ($length > strlen($string))
        {
            $length = strlen($string);
        }

        // Find the position of the next space character after the desired length
        $end = strpos($string, ' ', $start + $length);

        // If no space is found, set end to the end of the string
        if ($end === false)
        {
            $end = strlen($string);
        }

        // Extract the substring
        $subword = substr($string, $start, $end - $start);

        return $subword;
    }

    public static function searchForValueFromArray(string $search, $column, $array, $tipe = '')
    {
        foreach ($array as $key => $val)
        {
            if ($tipe == 'array')
            {
                if (is_array($val[$column]))
                {
                    if (in_array($search, $val[$column]))
                    {
                        foreach ($val['sub_menu'] as $key => $val)
                        {
                            if ($val[$column] == $search)
                            {
                                return $val;
                            }
                        }
                    }
                }
            }
            else
            {
                if ($val[$column] == $search)
                {
                    return $val;
                }
            }
        }
        return null;
    }

    public static function searchForValueFromArray2($search, $column, $akses, $array, $tipe = '')
    {
        foreach ($array as $key => $val)
        {
            if ($tipe == 'array')
            {
                if (is_array($val[$column]))
                {
                    if (in_array($search, $val[$column]) && in_array($akses, $val['akses']))
                    {
                        foreach ($val['sub_menu'] as $key => $val)
                        {
                            if ($val[$column] == $search && in_array($akses, $val['akses']))
                            {
                                return $val;
                            }
                        }
                    }
                }
            }
            else
            {
                if ($val[$column] == $search && in_array($akses, $val['akses']))
                {
                    return $val;
                }
            }
        }
        return null;
    }

    public static function searchForValueFromArrayByArray($search, $column, $array)
    {

        $i = 0;
        foreach ($array as $key => $val)
        {
            if (in_array($val[$column], $search))
            {
                $valReturn[$i] = $val;
                $i++;
            }
        }
        return $valReturn;
    }

    public static function whereFromArray($search, $column, $array)
    {

        $return_array = array();

        foreach ($array as $key => $val)
        {
            if ($val[$column] == $search)
            {
                array_push($return_array, $val);
            }
        }
        return $return_array;
    }

    public static function like_match($pattern, $subject)
    {

        $pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
        return (bool) preg_match("/^{$pattern}$/i", $subject);
    }

    public static function whereLikeFromArray($search, $column, $array)
    {

        $return_array = array();

        foreach ($array as $key => $val)
        {
            if (self::like_match('%' . $search . '%', $val[$column]) != false)
            {
                array_push($return_array, $val);
            }
        }
        return $return_array;
    }

    // function highLight($string, $keyword) {

    //     if (!empty($keyword)) {

    //         return preg_replace("/\w*?$keyword\w*/i", "<b style='color: red;'>$0</b>", $string);
    //     } else {

    //         return $string;
    //     }
    // }

    public static function formatCurrency($number, $prefix = '0', $suffix = '0')
    {

        return ($prefix == '1' ? 'Rp. ' : '') . number_format($number, 0, '', '.') . ($suffix == '1' ? ',-' : '');
    }

    public static function checkNotNull($value, $is_zeronull = "0")
    {
        if ($value === NULL || $value === '' || ($is_zeronull == "1" && $value == 0))
        {
            return NULL;
        }
        return $value;
    }

    public static function printJson($json, $pretty = '')
    {

        define('JSON_PRETTY_PRINT', 128);

        if ($pretty != 'without')
        {
            print "<pre>";
        }
        print_r(json_encode($json, JSON_PRETTY_PRINT));
        if ($pretty != 'without')
        {
            print "</pre>";
        }
    }

    public static function toastifyColor($type)
    {

        if ($type == 'error')
        {

            $color = '#dc3545';
        }
        else if ($type == 'success')
        {

            $color = '#198754';
        }
        else
        {

            $color = '#435ebe';
        }

        return $color;
    }

    public static function lowerAndUCwords($string)
    {
        $return = strtolower($string);

        if (strpos($return, 'pt') !== false)
        {
            $return = str_replace('pt', '', $return);
            $return = ucwords($return);
            $return = 'PT' . $return;
        }
        else
        {
            $return = ucwords($return);
        }

        return $return;
    }

    public static function highLight($string, $key)
    {

        if ($key == null)
        {

            return $string;
        }
        else
        {
            $string = e($string);
            $key = e($key);
            $chString = str_split($string);
            $lenString = strlen($string);
            $lenKey = strlen($key);
            $strResult = $chString;

            for ($i = 0; $i < count($chString); $i++)
            {

                $strKey = "";

                for ($a = $i; $a < ($i + $lenKey); $a++)
                {
                    if ($a < $lenString)
                    {
                        $strKey .= $chString[$a];
                    }
                }

                if (strtolower($strKey) == strtolower($key))
                {

                    for ($b = $i; $b < ($i + $lenKey); $b++)
                    {
                        if ($b < $lenString)
                        {
                            $strResult[$b] = "<b><font color='red'>" . $chString[$b] . "</font></b>";
                        }
                    }
                }
            }

            return implode("", $strResult);

            // 
            // $keywords = [$key];

            // // Escape special characters in keywords to avoid regex issues
            // $escapedKeywords = array_map('preg_quote', $keywords);

            // // Create a regex pattern to match any of the keywords
            // $pattern = '/\b(' . implode('|', $escapedKeywords) . ')\b/i';

            // // Use preg_replace_callback to replace matched keywords with highlighted version
            // return preg_replace_callback($pattern, function ($matches)
            // {
            //     return "<b><font color='red'>" . $matches[0] . "</font></b>";
            // }, $string);
            // 

            // $strResult = str_ireplace($key, "<b><font color='red'>" . $key . "</font></b>", $string);
            // $strResult = preg_replace("/\b" . preg_quote($key, '/') . "\b/i", "<b><font color='red'>" . $key . "</font></b>", $string);

            // return $strResult;
        }
    }

    public static function highLight_iframe($string, $key)
    {
        if ($key == null)
        {
            return $string;
        }
        else
        {
            $pattern = '/(?!<[^>]*?>)(' . preg_quote($key, '/') . ')(?![^<]*?>)/i';

            // $callback = function ($matches) {
            //     return '<b><span style="color: red;">' . $matches[0] . '</span></b>';
            // };

            // $modifiedContent = preg_replace_callback($pattern, $callback, $string);

            // return $modifiedContent;

            // Split the content by HTML tags and entities
            $segments = preg_split('/(&[^;]+;|<[^>]+>)/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);

            // Highlight the substring in non-tag, non-entity segments
            foreach ($segments as &$segment)
            {
                if ($segment === strip_tags($segment) && !preg_match('/&[^;]+;/', $segment))
                {  // If the segment is not an HTML tag or entity
                    $segment = preg_replace('/(' . preg_quote($key, '/') . ')/i', '<b><span style="color: red;">$1</span></b>', $segment);
                }
            }

            // Reconstruct the content
            return implode('', $segments);
        }
    }

    public static function checkAktif($is_aktif)
    {
        if ($is_aktif == '1')
        {
            $return = '<button type="button" class="text-white p-1 border-0 bg-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Aktif" style="cursor: default; min-width: 26px;">
                <i class="fa fa-check"></i>
            </button>';
        }
        else
        {
            $return = '<button type="button" class="text-white p-1 border-0 bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Non Aktif" style="cursor: default; min-width: 26px;">
                <i class="fa fa-times"></i>
            </button>';
        }

        return $return;
    }

    public static function checkAktif_2($is_aktif)
    {
        if ($is_aktif == 'ya')
        {
            $return = '<button type="button" class="text-white p-1 border-0 bg-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Aktif" style="cursor: default; min-width: 26px;">
                <i class="fa fa-check"></i>
            </button>';
        }
        else
        {
            $return = '<button type="button" class="text-white p-1 border-0 bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tidak Aktif" style="cursor: default; min-width: 26px;">
                <i class="fa fa-times"></i>
            </button>';
        }

        return $return;
    }

    public static function checkStatusPembayaran($is_aktif)
    {
        if ($is_aktif == '1')
        {
            $return = '<button type="button" class="text-white p-1 border-0 bg-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Sudah Dilakukan Pembayaran" style="cursor: default; min-width: 26px;">
                <i class="fa fa-check"></i>
            </button>';
        }
        else
        {
            $return = '<button type="button" class="text-white p-1 border-0 bg-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Belum Dilakukan Pembayaran" style="cursor: default; min-width: 26px;">
                <i class="fa fa-times"></i>
            </button>';
        }

        return $return;
    }

    public static function checkValidasi($is_validated)
    {

        if ($is_validated == '1')
        {
            $return = '<button type="button" class="text-white p-1 border-0 bg-success" style="cursor: default; min-width: 26px;">
                <i class="fa fa-check"></i>
            </button>';
        }
        else
        {
            $return = '<button type="button" class="text-white p-1 border-0 bg-danger" style="cursor: default; min-width: 26px;">
                <i class="fa fa-times"></i>
            </button>';
        }

        return $return;
    }

    public static function getImageByTag($str)
    {

        if (strpos($str, 'src="') !== false)
        {

            $str_ex = explode('src="', $str);
            $src_ex = explode('"', $str_ex[1]);
            $hasil = $src_ex[0];
        }
        else
        {

            $hasil = "";
        }

        return $hasil;
    }

    public static function getImageByTagFull($str)
    {

        if (strpos($str, 'src="') !== false)
        {

            preg_match('#(<img.*?>)#', $str, $matches);
            $hasil = $matches[0];
        }
        else
        {

            $hasil = "";
        }

        return $hasil;
    }

    public static function generateRandomString($length = 5)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function getJenisKelamin($jenis_kelamin)
    {

        if (strtolower($jenis_kelamin) == 'l')
        {
            $return = 'Laki-laki';
        }
        else if (strtolower($jenis_kelamin) == 'p')
        {
            $return = 'Perempuan';
        }
        else
        {

            $return = '-';
        }

        return $return;
    }

    public static function tipeYaAtauTidak($tipe)
    {

        if ($tipe == 'Y')
        {

            $return = 'Ya';
        }
        else if ($tipe == 'T')
        {

            $return = 'Tidak';
        }
        else
        {
            $return = '';
        }

        return $return;
    }

    public static function defaultRouteUser($tipeUser, $username)
    {
        $return = '';

        $return = 'dashboard';

        return $return;
    }

    public static function strSameWidth($string, $maxlength, $minlength, $addon, $right = '')
    {

        $maxlength += 3;

        $times = ($maxlength >= strlen($string) ? ($maxlength - strlen($string)) : ($minlength - strlen($string))) + $addon + ($right == '1' ? 1 : 0);

        return $string . str_repeat('&nbsp;', $times) . ($right == '1' ? '|' : '');
    }

    public static function uniqueFileName($filename)
    {

        return date("YmdHis") . "-" . $filename;
    }

    public static function getTargetFilename($file_name)
    {

        $target_file = preg_replace("/[^a-z0-9\_\-\.]/i", '', pathinfo($file_name, PATHINFO_FILENAME));
        $target_file = self::uniqueFileName($target_file);

        if (strlen($target_file) > 188)
        {
            $target_file = substr($target_file, 0, 188);
        }

        $target_file .=  '.' . pathinfo($file_name, PATHINFO_EXTENSION);
        return $target_file;
    }

    public static function stdClassToArray($stdClass)
    {
        $return = json_decode(json_encode($stdClass), true);

        return $return;
    }

    public static function generatePassword($length = 8)
    {
        $lower = "abcdefghijklmnopqrstuvwxyz";
        $upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $number = "0123456789";
        $symbol = "!@#$%^&*()_+[]{}<>?";

        // wajib masing-masing 1
        $password = [
            $lower[random_int(0, strlen($lower) - 1)],
            $upper[random_int(0, strlen($upper) - 1)],
            $number[random_int(0, strlen($number) - 1)],
            $symbol[random_int(0, strlen($symbol) - 1)],
        ];

        // tambah sisa karakter random bebas
        $all = $lower . $upper . $number . $symbol;
        for ($i = count($password); $i < $length; $i++)
        {
            $password[] = $all[random_int(0, strlen($all) - 1)];
        }

        // acak urutannya biar nggak selalu sama
        shuffle($password);

        return implode('', $password);
    }

    public static function convertKeNoWa($nomor_hp)
    {
        // Hapus semua non-digit
        $nomor_hp = preg_replace('/\D/', '', $nomor_hp);

        // Mengonversi input ke integer untuk memastikan hanya angka yang diproses
        $nomor_hp = (int) $nomor_hp;

        // Mengonversi kembali ke string untuk memastikan hasilnya adalah string angka
        $nomor_hp = (string) $nomor_hp;

        if (empty($nomor_hp))
        {
            return '';
        }

        // Jika dimulai dengan 0, ganti dengan 62
        if (substr($nomor_hp, 0, 1) == '0')
        {
            $nomor_hp = preg_replace('/^0/', '62', $nomor_hp);
        }
        else
        {
            if (substr($nomor_hp, 0, 2) != '62')
            {
                // Jika tidak dimulai dengan 0, tambahkan 62 di depan
                $nomor_hp = '62' . $nomor_hp;
            }
        }

        // Mengonversi hasil menjadi integer
        return $nomor_hp;
    }
}
