<?php

namespace App\Helpers;

use App\Http\Controllers\CMS\BaseCMSController;

class Dictionary extends BaseCMSController
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getAgama($id = null)
    {
        $array = [
            '1' => 'Islam',
            '2' => 'Protestan',
            '3' => 'Katolik',
            '4' => 'Hindu',
            '5' => 'Budha',
            '6' => 'Khonghucu',
        ];

        if ($id !== null)
        {
            return $array[$id] ?? null;
        }

        return $array;
    }

    public static function getJenjangPendidikan($id = null)
    {
        $array = [
            '1' => 'SD',
            '2' => 'SMP',
            '3' => 'SMA',
            '4' => 'D1',
            '5' => 'D3',
            '6' => 'D4',
            '7' => 'S1',
            '8' => 'S2',
            '9' => 'S3',
            '10' => 'Lainnya',
        ];

        if ($id !== null)
        {
            return $array[$id] ?? null;
        }

        return $array;
    }

    public static function tipeUser($tipeUser)
    {
        $return = '';

        if (in_array($tipeUser, [self::$GLO_TIPEUSER_ADMIN]))
        {
            $return = 'Administrator';
        }
        else if (in_array($tipeUser, [self::$GLO_TIPEUSER_MAHASISWA]))
        {
            $return = 'Mahasiswa';
        }

        return $return;
    }
}
