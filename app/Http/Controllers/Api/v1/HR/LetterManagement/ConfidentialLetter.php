<?php

namespace App\Http\Controllers\Api\v1\HR\LetterManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Common\UserModel;
use App\Models\Common\UserRoleModel;
use App\Models\HR\LetterManagement\Confidential;

use Package\Common\Message;
use Package\Common\VerifyAccount;
use Carbon\Carbon;
use DB;


class ConfidentialLetter extends Controller
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
                $username = $r->input('username');
                $check = Confidential::where([
                    'username' => $username
                ])->count();
                if($check < 1){
                    $create = Confidential::create([
                        'username' => $username,
                        'confidential' => 'Y'
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

    public function DataList(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                if($r->input('search')){
                    $data = Confidential::where('username','LIKE','%'.$r->input('search').'%')
                            ->paginate(10);
                }
                else{
                    $data = Confidential::paginate(10);
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
                $delete = Confidential::where('id',$id);
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
}
