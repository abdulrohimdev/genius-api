<?php

namespace App\Http\Controllers\Api\v1\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

//direktory App/packages/Common/
use Package\Common\Message;
use Package\Common\VerifyAccount;
use Package\Common\Module as MyModule;
use Package\Common\MyTree;
use BlueM\Tree;


class Module extends Controller
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

    /*
        method : post
        url api : /api/v1/user-access
        with header apikey and secretkey
        function: AccessModule
    */
    public function AccessModule(Request $r,$paginate=false,$perpage=false){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            return Response()->json([
                'status' => true,
                'program'   => $this->verify->GetProgram($paginate,$perpage),
                'profile'   => [
                    'fullname' => $user->fullname,
                    'employeeid'=> $user->employee_id ?? "",
                    'company' => $user->company_code ?? "",
                    'photo' => $user->photo ?? "",
                ]
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function SearchModule(Request $r,$paginate=false,$perpage=false){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            return Response()->json([
                'status' => true,
                'program'   => $this->verify->SearchProgram($paginate,$perpage,$r->input('apps'))
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function SearchModuleCode(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $program = $this->verify->SearchProgramCode($r->input('apps'));
            if($program){
                return Response()->json([
                    'status' => true,
                    'program'   => $program
                ]);
            }
            return Response()->json([
                'status' => false,
                'program' => [],
                'message' => $this->message->get(11,['use' => true,'lang' => 'en'])
            ]);

        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function Tree(Request $r){
            $id = $r->input('id');
            $module = new MyModule();
            $module = $module->GetApp();
            $tree = new MyTree($module);
            if($id === '#' || !$id){
                $self  = $tree->getSelf(1);
            }
            else{
                $self = $tree->getSelf($id)->getChildren();
            }
            return Response()->json($self->see(true),200,[],JSON_PRETTY_PRINT);
    }

    public function GetStructureRole(Request $r){
        $role = $r->input('role');
        $id = $r->input('id');
        $mymodule = new MyModule();
        $app = $mymodule->GetApp();
        $module = $mymodule->GetModuleOfRole($role);
        $tree = new MyTree($app);
        $list = [];

        foreach($module as $moduleItem){
            $self = $tree->getSelf($moduleItem->id)->GetAncestorsAndSelf();
            $data = $self->see(false);
            foreach($data as $item){
                array_push($list,$item);
            }
        }
        $is_tree = array_unique($list);
        $treeJSON= json_encode($is_tree);
        $toArray = (array) json_decode($treeJSON,true);
        if(count($toArray) > 0){
            $buildTree = new MyTree($toArray);
            if($id === '#' || !$id){
                $is_self  = $buildTree->getSelf(1);
            }
            else{
                $is_self = $buildTree->getSelf($id)->getChildren();
            }
            return Response()->json($is_self->see(true),200,[],JSON_PRETTY_PRINT);

        }
        else{
            return Response()->json([],200,[],JSON_PRETTY_PRINT);
        }
    }

    public function CreateForRole(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $role = $r->input('role');
            $id   = $r->input('id');
            $app  = new MyModule();
            $module= $app->whereIn("id",$id);
            $data = [];
            foreach($module as $item){
                if($item->application_code){
                    array_push($data,[
                        'role_code_id' => $role,
                        'application_code' => $item->application_code
                    ]);
                }
            }
            $app->RoleGroupCreate($data);
            return Response()->json([
                'status' => true,
                'message' => $this->message->get(16,[
                    'use' => true,
                    'lang' => $user->language])]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);

    }

    public function DeleteForRole(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $role = $r->input('role');
            $id   = $r->input('id');
            $app  = new MyModule();
            $module= $app->whereIn("id",$id);
            $data = [];
            foreach($module as $item){
                if($item->application_code){
                    array_push($data,[
                        'role_code_id' => $role,
                        'application_code' => $item->application_code
                    ]);
                }
            }
            $delete = $app->RoleGroupDelete($data);
            if($delete){
                return Response()->json([
                    'status' => true,
                    'data' => $delete,
                    'message' => $this->message->get(18,[
                        'use' => true,
                        'lang' => $user->language])]);
            }
            else{
                return Response()->json([
                    'status' => false,
                    'data'   => $delete,
                    'message' => $this->message->get(19,[
                        'use' => true,
                        'lang' => $user->language])]);
            }
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);

    }

}
