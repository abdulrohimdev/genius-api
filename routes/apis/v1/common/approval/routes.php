<?php
$dir = 'App\Http\Controllers\Api\v1\Common\Approval';

Route::post('v1/common/approval/create',$dir.'\DoctypeMaster@Store'); 
Route::get('v1/common/approval/list',$dir.'\DoctypeMaster@DataList'); 
Route::post('v1/common/approval/check',$dir.'\DoctypeMaster@Check'); 
Route::post('v1/common/approval/delete',$dir.'\DoctypeMaster@Delete'); 
Route::post('v1/common/approval/update',$dir.'\DoctypeMaster@Update'); 
