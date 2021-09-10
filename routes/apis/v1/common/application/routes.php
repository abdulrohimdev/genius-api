<?php
$dir = 'App\Http\Controllers\Api\v1\Common\Application';

Route::post('v1/common/application/create',$dir.'\Apps@Store'); 
Route::get('v1/common/application/list',$dir.'\Apps@DataList'); 
// Route::post('v1/common/application/check',$dir.'\DoctypeMaster@Check'); 
Route::post('v1/common/application/delete',$dir.'\Apps@Delete'); 
Route::post('v1/common/application/update',$dir.'\Apps@Update'); 


Route::post('v1/common/app-group/store',$dir.'\AppGroup@Store'); 
Route::post('v1/common/app-group/delete',$dir.'\AppGroup@Delete'); 
