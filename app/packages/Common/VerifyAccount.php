<?php
/* 
    Author by Abdul Rohim
    Library Code Verify Account
*/

namespace Package\Common;

use App\Models\Common\UserModel;
use App\Models\Common\UserRoleModel as MyRole;
use App\Models\Common\RoleModel as Role;
use App\Models\Common\RoleGroupModel as RoleGroup;
use DB;

class VerifyAccount
{
    public $user;
    public $count;

    public function __construct($apikey,$secretkey){
        $this->user = UserModel::where(['api_key' => $apikey,'secret_key' => $secretkey]);
        return $this;
    }

    public function check($apikey,$secretkey){
        $this->user = UserModel::where(['api_key' => $apikey,'secret_key' => $secretkey]);
        return $this;
    }

    public function count(){
        return $this->user->count();
    }

    public function first(){
        return $this->user->first();
    }

    public function GetProgram($paginate = false,$perpage = false){
        $user = $this->user->first();
        $program = DB::table("user_roles")
                        ->join('role_groups','role_groups.role_code_id','user_roles.role_code')
                        ->join('applications','applications.app_code','role_groups.application_code')
                        ->select('applications.app_code',
                                 'applications.app_name',
                                 'applications.app_description',
                                 'applications.app_route_frontend_web',
                                 'applications.app_route_frontend_mobile',
                                 'applications.app_icon_class',
                                 'applications.app_icon_image',
                        )->whereIn('user_roles.secretkey',[$user->secret_key]);
        if($paginate){
            return $program->paginate($perpage);
        }
        else{
            return $program->get();
        }
    }

    public function SearchProgram($paginate = false,$perpage = false,$apps){
        $user = $this->user->first();
        $program = DB::table("user_roles")
                        ->join('role_groups','role_groups.role_code_id','user_roles.role_code')
                        ->join('applications','applications.app_code','role_groups.application_code')
                        ->select('applications.app_code',
                                 'applications.app_name',
                                 'applications.app_description',
                                 'applications.app_route_frontend_web',
                                 'applications.app_route_frontend_mobile',
                                 'applications.app_icon_class',
                                 'applications.app_icon_image',
                        )
                        ->where('applications.app_name','LIKE',"%{$apps}%")
                        ->whereIn('user_roles.secretkey',[$user->secret_key]);
        if($paginate){
            return $program->paginate($perpage);
        }
        else{
            return $program->get();
        }
    }

    public function SearchProgramCode($apps){
        $user = $this->user->first();
        $program = DB::table("user_roles")
                        ->join('role_groups','role_groups.role_code_id','user_roles.role_code')
                        ->join('applications','applications.app_code','role_groups.application_code')
                        ->select('applications.app_code',
                                 'applications.app_name',
                                 'applications.app_description',
                                 'applications.app_route_frontend_web',
                                 'applications.app_route_frontend_mobile',
                                 'applications.app_icon_class',
                                 'applications.app_icon_image',
                        )
                        ->where('applications.app_code',$apps)
                        ->whereIn('user_roles.secretkey',[$user->secret_key]);
        return $program->first();
    }

    public function CheckModulePermissions(){

    }
    

}