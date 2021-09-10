<?php

namespace App\Http\Controllers\Api\v1\Common\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\Application\Application as AppModel;
use App\Models\Common\Application\ApplicationGroup as AppGroupModel;
use Illuminate\Support\Facades\Hash;

//direktory App/packages/Common/
use Package\Common\Message; 
use Package\Common\VerifyAccount; 
use Package\Common\Module as MyModule; 
use Package\Common\MyTree; 
use BlueM\Tree;


class AppGroup extends Controller
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

    public function Store(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            try{
                
                $store = AppGroupModel::create($r->all());
                if($store){
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


    public function Delete(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $id = $r->input('id');
                $data = AppGroupModel::whereIn('id',[$id]);
                $child= AppGroupModel::whereIn('app_group_parent',[$id]);
                if($data->count() > 0){
                    if($data->delete()){
                        if($child->count() > 0){
                            if($child->delete()){
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
