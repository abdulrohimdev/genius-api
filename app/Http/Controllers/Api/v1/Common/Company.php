<?php

namespace App\Http\Controllers\Api\v1\Common;

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

use App\Models\Common\Company as CompanyModel;

class Company extends Controller
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

    public function DataList(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                if($r->input('search')){
                    $data = CompanyModel::where('company_name','LIKE','%'.$r->input('search').'%')
                            ->orWhere('company_code','LIKE','%'.$r->input('search').'%')
                            ->paginate(5);
                }
                else{
                    $data = CompanyModel::paginate(5);
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

    public function Store(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $check = CompanyModel::where(['company_code' => $r->input('company_code')])->count();
                if($check < 1){
                    $create = CompanyModel::create($r->all());
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
                    'message' => $this->message->get(22,[
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

    public function Update(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $check = CompanyModel::where(['company_code' => $r->input('company_code')])->count();
                if($check > 0){
                    $update = CompanyModel::where(['company_code' => $r->input('company_code')])
                    ->update($r->all());
                    if($update){
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
                    'message' => $this->message->get(22,[
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
                $check = CompanyModel::where(['company_code' => $r->input('company_code')])->count();
                if($check){
                    $delete = CompanyModel::where(['company_code' => $r->input('company_code')]);
                    if($delete->delete()){
                        return Response()->json([
                            'status' => true,
                            'message' => $this->message->get(18,[
                                'use' => true,
                                'lang' => $user->language])]);                                
                    }
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(19,[
                            'use' => true,
                            'lang' => $user->language])]);                        
       
                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(23,[
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

    public function ListOne(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $check = CompanyModel::where(['company_code' => $r->input('company_code')])->count();
                if($check){
                    $data = CompanyModel::where(['company_code' => $r->input('company_code')])->first();
                    return Response()->json([
                        'status' => true,'data' => $data
                    ]);                                                
                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(23,[
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



}
