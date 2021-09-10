<?php

namespace App\Http\Controllers\Api\v1\Common\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Common\UserModel;
use App\Models\Common\UserRoleModel;

use Package\Common\Message;
use Package\Common\VerifyAccount;
use Carbon\Carbon;
use DB;

use App\Models\Common\Application\Application as AppModel;
use App\Models\Common\Application\ApplicationGroup as AppGroupModel;

class Apps extends Controller
{
    public $message;
    public $verify;

    function __construct(Request $r){
        $this->message = new Message();
        $this->verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
    }

    public function store(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $create = AppModel::create([
                    'app_code' => $r->input('app_code'),
                    'app_name' => $r->input('app_name'),
                    'app_description' => $r->input('app_description'),
                    'app_route_frontend_web' => $r->input('app_route_frontend_web'),
                    'app_icon_class' => $r->input('app_icon_class'),
                ]);
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
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $e->getMessage()]);                    
            }
        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);
    }

    public function DataList(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                if($r->input('search')){
                    $data = AppModel::where('app_code','LIKE','%'.$r->input('search').'%')
                            ->orWhere('app_name','LIKE','%'.$r->input('search').'%')
                            ->paginate(10);
                }
                else{
                    $data = AppModel::paginate(10);
                }
                return Response()->json([
                    'status' => true,
                    'data'   => $data]);                    
            }
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $e->getMessage()]);                    
            }
        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);        
    }

    public function Delete(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $id = $r->input('id');
                $data = AppModel::where('id',$id);
                $get  = $data->first();
                $check= AppGroupModel::where(['application_code'=> $get->app_code]);
                if($check->count() > 0){
                    $check->delete();                    
                }

                if($data->delete()){
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
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $e->getMessage()]);                    
            }
        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);        
    }

    public function Update(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $id = $r->input('id');
                $data = AppModel::where('id',$id)->first();
                $dataGroup = AppGroupModel::where(['application_code' => $data->app_code]);
                if($dataGroup->count() > 0){
                    $dataAppGroup = $dataGroup->first();
                    $dataAppGroup->application_code = $r->input('app_code');
                    $dataAppGroup->app_group_name = $r->input('app_name');
                    $dataAppGroup->update();
                }
                
                $data->app_code = $r->input('app_code');
                $data->app_name = $r->input('app_name');
                $data->app_description = $r->input('app_description');
                $data->app_route_frontend_web = $r->input('app_route_frontend_web');
                $data->app_icon_class = $r->input('app_icon_class');
                if($data->update()){
                    return Response()->json([
                        'status' => true,
                        'message' => $this->message->get(13,[
                            'use' => true,
                            'lang' => 'en'])]);        
                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(12,[
                        'use' => true,
                        'lang' => 'en'])]);        
        }
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $e->getMessage()]);                    
            }
        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);        
    }

}
