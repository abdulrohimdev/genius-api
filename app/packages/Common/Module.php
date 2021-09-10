<?php
/* 
    Author by Abdul Rohim
    Library Code Module
*/

namespace Package\Common;

use App\Models\Common\UserModel;
use App\Models\Common\ApplicationGroup;
use App\Models\Common\RoleGroupModel;
use DB;

class Module
{
    public function GetApp(){
        $data = ApplicationGroup::all();
        $temp = [];        
        foreach($data as $item){
            array_push($temp,[
                'id' => (int) $item->id,
                'title' => $item->app_group_name,
                'parent' => (int) $item->app_group_parent,
                'app_code' => $item->application_code
            ]);
        }
        return $temp;
    }

    public function GetModuleOfRole($role = ''){
        $data = DB::table('role_groups')
                ->join('application_groups','application_groups.application_code','role_groups.application_code')
                ->select('application_groups.*')
                ->where('role_groups.role_code_id',$role)
                ->get();
        return $data;
    }       

    public function HasChildren($id){
        $data = ApplicationGroup::where(['app_group_parent' => $id])->get();
        if(count($data) > 0 ){
            return (true);
        }
        else{
            return (false);
        }        
    }

    public function whereIn($field,$array){
        $data = ApplicationGroup::whereIn($field,$array)->get();
        return $data;
    }

    public function RoleGroupCreate($data){
        try{
            foreach($data as $item){
                $check = RoleGroupModel::where($item);
                if($check->count() < 1){
                    $create = RoleGroupModel::create($item);
                }
            }
            return (true);    
        }
        catch(\Exception $e){
            return (false);    
        }
    }

    public function RoleGroupDelete($data){
        $roleCode = [];
        $appCode = [];
        foreach($data as $item){
            $roleCode[] = $item['role_code_id'];
            $appCode[] = $item['application_code'];
        }

        try{
            $role = RoleGroupModel::whereIn("role_code_id",$roleCode)
                                    ->whereIn("application_code",$appCode)
                                    ->delete();
            return $role;
        }   
        catch(\Exception $e){
            return (false);
        }
    }

}