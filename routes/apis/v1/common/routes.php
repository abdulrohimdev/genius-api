<?php
$dir = 'App\Http\Controllers\Api\v1\Common';

Route::post('v1/access-module/{paginate}/{perpage}',$dir.'\Module@AccessModule');
Route::post('v1/search-module/{paginate}/{perpage}',$dir.'\Module@SearchModule');
Route::post('v1/search-module-code',$dir.'\Module@SearchModuleCode');
Route::post('v1/backup',$dir.'\Backup@run');

Route::post('v1/profile',$dir.'\Auth@profile');
Route::post('v1/register-app-id',$dir.'\Auth@registerAppId');
Route::post('v1/update-photo-profile',$dir.'\Auth@UpdatePhotoProfile');
Route::post('v1/profiles',$dir.'\Auth@profiles');
Route::post('v1/change-password',$dir.'\Auth@ChangePassword');
Route::post('v1/oauth',$dir.'\Auth@credential');

Route::post('v1/role-check',$dir.'\Role@RoleChecking');
Route::post('v1/role-name',$dir.'\Role@RoleName');
Route::post('v1/role-create',$dir.'\Role@RoleCreate');
Route::post('v1/role-delete',$dir.'\Role@RoleDelete');
Route::post('v1/role',$dir.'\Role@RoleData');
Route::post('v1/role-update-description',$dir.'\Role@UpdateDescription');

Route::get('v1/module-tree',$dir.'\Module@Tree');
Route::get('v1/module-structure-role',$dir.'\Module@GetStructureRole');

Route::post('v1/module-create-for-role',$dir.'\Module@CreateForRole');
Route::post('v1/module-delete-for-role',$dir.'\Module@DeleteForRole');

Route::post('v1/user-create',$dir.'\User@store');
Route::post('v1/user-delete',$dir.'\User@delete');
Route::post('v1/user-update',$dir.'\User@update');
Route::post('v1/account-list',$dir.'\User@AccountList');
Route::post('v1/account-personal',$dir.'\User@AccountPersonal');
Route::post('v1/change-user-password',$dir.'\User@UpdatePasswordUser');
Route::post('v1/common/import-user',$dir.'\User@importUser');


Route::post('v1/application-list',$dir.'\Application@List');
Route::post('v1/application-rule',$dir.'\Application@Rule');

Route::get('v1/common/company/list',$dir.'\Company@DataList');
Route::post('v1/common/company/list-one',$dir.'\Company@ListOne');
Route::post('v1/common/company/store',$dir.'\Company@Store');
Route::post('v1/common/company/delete',$dir.'\Company@Delete');
Route::post('v1/common/company/update',$dir.'\Company@Update');

Route::get('v1/common/datagroup/show-column',$dir.'\Utility\Datagroup@GetColumnFromTable');
Route::get('v1/common/datagroup/show-table',$dir.'\Utility\Datagroup@GetListTable');
Route::post('v1/common/datagroup/export',$dir.'\Utility\Datagroup@ExportDataFromTable');
Route::post('v1/common/datagroup/insert',$dir.'\Utility\Datagroup@Store');
Route::post('v1/common/datagroup/delete',$dir.'\Utility\Datagroup@Delete');
Route::post('v1/common/datagroup/user-table',$dir.'\Utility\Datagroup@user_table');

Route::get('v1/common/current-version-mobile',$dir.'\VersionMobileController@CurrentVersion');
Route::get('v1/common/news',$dir.'\News\News@getNews');
Route::get('v1/common/birthday',$dir.'\Birthday\Birthdays@birthdayEuclid');//before is today()
Route::get('v1/common/birthday-euclid',$dir.'\Birthday\Birthdays@birthdayEuclid');

