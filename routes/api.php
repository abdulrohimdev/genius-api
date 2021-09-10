<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

require_once "apis/v1/common/routes.php";
require_once "apis/v1/common/approval/routes.php";
require_once "apis/v1/common/application/routes.php";
require_once "apis/v1/hr/letter-management/routes.php";
require_once "apis/v1/hr/leave/routes.php";
require_once "apis/v1/problem-management/routes.php";
