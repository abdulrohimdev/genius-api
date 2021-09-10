<?php

namespace App\Http\Controllers\Api\v1\Common\Approval;


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

use App\Models\Common\Approval\DoctypeMaster as DoctypeMasterModel;

class DoctypeMaster extends Controller
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
                $doctype_code = strtoupper($r->input('doctype_code'));
                $doctype_name = $r->input('doctype_name');
                $create = DoctypeMasterModel::create([
                    'doctype_code' => $doctype_code,
                    'doctype_name' => $doctype_name
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
                    $data = DoctypeMasterModel::where('doctype_name','LIKE','%'.$r->input('search').'%')
                            ->paginate(10);
                }
                else{
                    $data = DoctypeMasterModel::paginate(10);
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
                $delete = DoctypeMasterModel::where('id',$id);
                if($delete->delete()){
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
                $doctype = DoctypeMasterModel::where('id',$id)->first();
                $doctype->doctype_code = strtoupper($r->input('doctype_code'));
                $doctype->doctype_name = $r->input('doctype_name');
                if($doctype->update()){
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

    public function Check(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $doctype = $r->input('doctype');
                $check  = DoctypeMasterModel::where(['doctype_code' => $doctype])->count();
                if($check > 0){
                    return Response()->json([
                        'status' => true,
                        'message' => $this->message->get(23,[
                            'use' => true,
                            'lang' => 'en'])]);        
                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(21,[
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
