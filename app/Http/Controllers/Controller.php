<?php

namespace App\Http\Controllers;

use App\Classes\MakeResponse;

abstract class Controller
{
    public function success()
    {
        return MakeResponse::success();
    }

    public function error()
    {
        return MakeResponse::error();
    }

    public function raw($data)
    {
        return MakeResponse::body($data);
    }
}
