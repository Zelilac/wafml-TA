<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CMS\BaseCMSController;

class FrontController extends BaseCMSController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('front.login');
    }
}
