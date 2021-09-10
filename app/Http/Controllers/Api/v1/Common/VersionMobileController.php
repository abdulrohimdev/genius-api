<?php

namespace App\Http\Controllers\Api\v1\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Common\VersionMobile;

class VersionMobileController extends Controller
{
    public function CurrentVersion(Request $r){
        $version = VersionMobile::all();
        return Response()->json(['version' => $version[0]]);
    }
}
