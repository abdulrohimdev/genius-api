<?php
$dir = 'App\Http\Controllers\Api\v1\HR\LetterManagement';

Route::post('v1/hr/type-of-letter/create',$dir.'\TypeOfLetter@Store'); 
Route::get('v1/hr/type-of-letter/list',$dir.'\TypeOfLetter@DataList'); 
Route::post('v1/hr/type-of-letter/delete',$dir.'\TypeOfLetter@Delete'); 
Route::post('v1/hr/type-of-letter/update',$dir.'\TypeOfLetter@Update'); 

Route::post('v1/hr/category-of-letter/create',$dir.'\CategoryOfLetter@Store'); 
Route::get('v1/hr/category-of-letter/list',$dir.'\CategoryOfLetter@DataList'); 
Route::get('v1/hr/category-of-letter/list-of-type',$dir.'\CategoryOfLetter@DataListByType'); 
Route::post('v1/hr/category-of-letter/delete',$dir.'\CategoryOfLetter@Delete'); 
Route::post('v1/hr/category-of-letter/update',$dir.'\CategoryOfLetter@Update'); 
Route::post('v1/hr/category-of-letter/get-category',$dir.'\CategoryOfLetter@GetCategory'); 

Route::post('v1/hr/area-of-letter/create',$dir.'\AreaOfLetter@Store'); 
Route::get('v1/hr/area-of-letter/list',$dir.'\AreaOfLetter@DataList'); 
Route::post('v1/hr/area-of-letter/delete',$dir.'\AreaOfLetter@Delete'); 
Route::post('v1/hr/area-of-letter/update',$dir.'\AreaOfLetter@Update'); 

Route::post('v1/hr/confidential/create',$dir.'\ConfidentialLetter@Store'); 
Route::get('v1/hr/confidential/list',$dir.'\ConfidentialLetter@DataList'); 
Route::post('v1/hr/confidential/delete',$dir.'\ConfidentialLetter@Delete'); 

Route::post('v1/hr/company-letter/create',$dir.'\CompanyLetter@Store'); 
Route::get('v1/hr/company-letter/list',$dir.'\CompanyLetter@DataList'); 
Route::post('v1/hr/company-letter/delete',$dir.'\CompanyLetter@Delete'); 
Route::post('v1/hr/company-letter/update',$dir.'\CompanyLetter@Update'); 
Route::get('v1/hr/company-letter/download',$dir.'\CompanyLetter@Download'); 
Route::post('v1/hr/company-letter/data',$dir.'\CompanyLetter@GetCountAndLetterNumber'); 

Route::post('v1/hr/department-of-letter/create',$dir.'\DeptForLetter@Store'); 
Route::get('v1/hr/department-of-letter/list',$dir.'\DeptForLetter@DataList'); 
Route::post('v1/hr/department-of-letter/delete',$dir.'\DeptForLetter@Delete'); 
Route::post('v1/hr/department-of-letter/update',$dir.'\DeptForLetter@Update'); 

Route::post('v1/hr/area-user-access/create',$dir.'\LetterUserArea@Store'); 
Route::get('v1/hr/area-user-access/list',$dir.'\LetterUserArea@DataList'); 
Route::post('v1/hr/area-user-access/delete',$dir.'\LetterUserArea@Delete'); 
Route::post('v1/hr/area-user-access/update',$dir.'\LetterUserArea@Update'); 
Route::get('v1/hr/area-user-access/access',$dir.'\LetterUserArea@GetAccessArea'); 


Route::post('v1/hr/company-user-access/create',$dir.'\CompanyUserAccess@Store'); 
Route::get('v1/hr/company-user-access/list',$dir.'\CompanyUserAccess@DataList'); 
Route::post('v1/hr/company-user-access/delete',$dir.'\CompanyUserAccess@Delete'); 
Route::post('v1/hr/company-user-access/update',$dir.'\CompanyUserAccess@Update'); 
Route::get('v1/hr/company-user-access/access',$dir.'\CompanyUserAccess@GetAccessCompany'); 

Route::post('v1/hr/type-user-access/create',$dir.'\TypeUserAccess@Store'); 
Route::get('v1/hr/type-user-access/list',$dir.'\TypeUserAccess@DataList'); 
Route::post('v1/hr/type-user-access/delete',$dir.'\TypeUserAccess@Delete'); 
Route::post('v1/hr/type-user-access/update',$dir.'\TypeUserAccess@Update'); 
Route::get('v1/hr/type-user-access/access',$dir.'\TypeUserAccess@GetAccessType'); 


Route::post('v1/hr/department-user-access/create',$dir.'\DepartmentUserAccess@Store'); 
Route::get('v1/hr/department-user-access/list',$dir.'\DepartmentUserAccess@DataList'); 
Route::post('v1/hr/department-user-access/delete',$dir.'\DepartmentUserAccess@Delete'); 
Route::post('v1/hr/department-user-access/update',$dir.'\DepartmentUserAccess@Update'); 
Route::get('v1/hr/department-user-access/access',$dir.'\DepartmentUserAccess@GetAccessDepartment'); 

Route::post('v1/hr/category-user-access/create',$dir.'\CategoryUserAccess@Store'); 
Route::get('v1/hr/category-user-access/list',$dir.'\CategoryUserAccess@DataList'); 
Route::post('v1/hr/category-user-access/delete',$dir.'\CategoryUserAccess@Delete'); 
Route::get('v1/hr/category-user-access/access',$dir.'\CategoryUserAccess@GetAccessCategory'); 

