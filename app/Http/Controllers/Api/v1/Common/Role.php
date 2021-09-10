<?php

namespace App\Http\Controllers\Api\v1\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Package\Common\Message; 
use Package\Common\VerifyAccount; 
use App\Models\Common\RoleModel;
use App\Models\Common\RoleGroupModel;
use App\Models\Common\UserRoleModel;

class Role extends Controller
{

    public $verify;
    public $message;
    public function __construct(Request $r){
        $this->message = new Message();
        $this->verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
    }
   
    public function RoleChecking(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $role = RoleModel::where(['role_code' => $r->input('role')]);
            if($role->count() > 0){
                return Response()->json([
                    'status' => false,
                    'data'   => $role->first(),
                    'message' => $this->message->get(14,[
                        'use' => true,
                        'lang' => $user->language])]);        
            }
            return Response()->json([
                'status' => true,
                'message' => $this->message->get(15,[
                    'use' => true,
                    'lang' => $user->language])]);        
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function RoleName(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $role = RoleModel::where(['role_code' => $r->input('role')]);
            if($role->count() > 0){
                $role = $role->first();
                return Response()->json([
                    'status' => true,
                    'role_name' => $role->role_description 
                ]);
            }
            return Response()->json([
                'status' => false,
                'message' => ''
            ]);        
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);   
    }

    public function RoleCreate(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $data = [
                'role_code' => $r->input('role_code'),
                'role_description' => $r->input('role_description'),
            ];
            $create = RoleModel::create($data);
            if($create){
                return Response()->json([
                    'status' => true,
                    'message' => $this->message->get(16,[
                        'use' => true,
                        'lang' => $user->language])]);           
            }
            return Response()->json([
                'status' => false,
                'message' => $this->message->get(17,[
                    'use' => true,
                    'lang' => $user->language])]);       
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);   
    }

    public function RoleData(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $role = RoleModel::where('role_code','like',"%".$r->input('role')."%");
            return Response()->json([
                'status' => true,
                'data' => $role->paginate(10)
            ]);           
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);   
    }

    public function UpdateDescription(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $role = RoleModel::where(['role_code' => $r->input('role_code')])->first();
            $role->role_description = $r->input('role_description');
            if($role->save()){
                return Response()->json([
                    'status' => true,
                    'message' => $this->message->get(13,[
                        'use' => true,
                        'lang' => $user->language])]);       
            }
            return Response()->json([
                'status' => false,
                'message' => $this->message->get(12,[
                    'use' => true,
                    'lang' => $user->language])]);   
            }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);   
    }

    public function RoleDelete(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $role = RoleModel::where(['role_code' => $r->input('role_code')]);
            if($role->count() > 0){
                $role_group = RoleGroupModel::whereIn('role_code_id',[$r->input('role_code')]);
                $user_role  = UserRoleModel::whereIn('role_code',[$r->input('role_code')]);
                if($role_group->count() > 0){
                    $role_group->delete();
                }
                if($user_role->count() > 0){
                    $user_role->delete();
                }
                if($role->delete()){
                    return Response()->json([
                        'status' => true,
                        'message' => $this->message->get(18,[
                            'use' => true,
                            'lang' => 'en'])]);               
                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(19,[
                        'use' => true,
                        'lang' => 'en'])]);           
            }
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);   
    }

}
