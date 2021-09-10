<?php
$directory = 'App\Http\Controllers\Api\v1\HR\ApprovalList';
$request = 'App\Http\Controllers\Api\v1\HR\Request';
Route::post('v1/hr/leave/list-approval',$directory.'\ApprovalList@GetList');
Route::post('v1/hr/leave/create-leave',$request.'\Leave@CreateLeave');
Route::post('v1/hr/leave/create-leave-v2',$request.'\Leave@CreateLeaveV2');
Route::post('v1/hr/leave/list-leave',$request.'\Leave@ListLeave');
Route::post('v1/hr/leave/leave-detail',$request.'\Leave@LeaveDetail');
Route::post('v1/hr/leave/leave-list-request',$request.'\Leave@LeaveListRequest');
Route::post('v1/hr/leave/leave-denied-accepted',$request.'\Leave@DeniedOrAccept');
Route::post('v1/hr/leave/leave-pending',$request.'\Leave@getNotificationPending');
Route::post('v1/hr/leave/leave-search-empid',$request.'\Leave@searchByEmployeeID');
Route::post('v1/hr/leave/leave-search-by-date',$request.'\Leave@searchByFromDate');
Route::post('v1/hr/leave/leave-security-action',$request.'\Leave@securityAction');
Route::post('v1/hr/leave/leave-search-unix',$request.'\Leave@searchByUnixNumber');
Route::post('v1/hr/leave/leave-record',$request.'\Leave@LeaveRecord');
Route::post('v1/hr/leave/leave-canceled',$request.'\Leave@LeaveCanceled');
Route::post('v1/hr/leave/update-leave',$request.'\Leave@UpdateLeave');
