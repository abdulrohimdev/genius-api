<?php
use App\Models\MgtProblems\FormCaseProblem;

$dir = 'App\Http\Controllers\Api\v1\MgtProblem';

Route::post('v1/problem-management/get-location',$dir.'\MgtMasterProblems@GetLocation');
Route::post('v1/problem-management/get-process',$dir.'\MgtMasterProblems@GetLineProcess');
Route::post('v1/problem-management/get-type',$dir.'\MgtMasterProblems@GetType');
Route::post('v1/problem-management/get-product',$dir.'\MgtMasterProblems@GetProduct');
Route::post('v1/problem-management/get-problem',$dir.'\MgtMasterProblems@GetProblem');
Route::post('v1/problem-management/create',$dir.'\MgtProblems@Store');
Route::post('v1/problem-management/have-problem',$dir.'\MgtProblems@GetProblems');
Route::post('v1/problem-management/delete',$dir.'\MgtProblems@destroy');
Route::post('v1/problem-management/get-data',$dir.'\MgtProblems@data');
Route::post('v1/problem-management/case/delete',$dir.'\MgtProblems@delete_case');
Route::post('v1/problem-management/case/create',$dir.'\MgtProblems@store_case');
Route::post('v1/problem-management/report/get-chart',$dir.'\MgtProblems@getChart');
Route::post('v1/problem-management/report/get-chart-reload',$dir.'\MgtProblems@chartReload');
Route::post('v1/problem-management/report/detail',$dir.'\MgtProblems@detail');
Route::post('v1/problem-management/report/product-detail',$dir.'\MgtProblems@productDetail');

Route::get('show',function(Request $r){
    $data = FormCaseProblem::where(['id' => 34])->first();
    // $img = file_get_contents("blob:http://localhost:52447/f81c416a-2190-4757-a498-5f6859fb0bba");
    // $img = base64_encode($img);
    // return $data;
    return "<img src='data:image/jpeg;base64,$data->image'>";
});
