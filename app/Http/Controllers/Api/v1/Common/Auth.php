<?php

namespace App\Http\Controllers\Api\v1\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common\UserModel;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Package\Common\Message;
use Package\Common\VerifyAccount;

class Auth extends Controller
{
    public $message;

    function __construct(){
        $this->message = new Message();
    }

    /*
        Check credential user account (Login Account)
        method : post
        url api : /api/v1/oauth
        with response apikey and secretkey
        function: credential
    */

    public function credential(Request $r){
        $username = $r->input('userid');
        $userpass = $r->input('userpass');
        $verify_userid = UserModel::where('username',$username)->first();
        if($verify_userid){
            if(Hash::check($userpass,$verify_userid->password)){
                return Response()->json([
                    'status' => true,
                    'message'=> $this->message->get(0,[
                                    'use' => true,
                                    'lang' => $verify_userid->language]),
                    'apikey' => $verify_userid->api_key,
                    'employee_id' => $verify_userid->employee_id,
                    'fullname' => $verify_userid->fullname,
                    'secretkey'=> $verify_userid->secret_key]);
            }
            return Response()->json([
                'status' => false,
                'message'=> $this->message->get(2,[
                            'use' => true,
                            'lang' => $verify_userid->language])]);
        }
        return Response()->json([
                'status' => false,
                'message'=> $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);
    }



    /*
        get profile user account
        method : post
        url api : /api/v1/profile
        with header apikey and secretkey
        function: profile
    */

    public function profile(Request $r){
        $message = new Message();
        $verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
        $user = $verify->first();
        if($user){
            return Response()->json(['status' => true,'data' => $user]);
        }
        return Response()->json(['status' => false,
            'data' => [],
            'message' => $message->get(3,[
            'use' => true,
            'lang' => 'en'])
        ]);
    }

    public function profiles(Request $r){
        $message = new Message();
        $verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
        $user = $verify->first();
        if($user){
            return Response()->json(['status' => true,'data' => [
                'company' => $user->company_code ?? "",
                'employeeid' => $user->employee_id ?? "",
                'fullname' => $user->fullname
            ]]);
        }
        return Response()->json(['status' => false,
            'data' => [],
            'message' => $message->get(3,[
            'use' => true,
            'lang' => 'en'])
        ]);
    }

    /*
        Change Password account
        method : post
        url api : /api/v1/change-password
        with header apikey and secretkey
        function: profile
    */

    public function ChangePassword(Request $r){
        $message = new Message();
        $verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
        $user = $verify->first();
        if($user){
            if(Hash::check($r->input('oldpassword'),$user->password)){
                $user->password = Hash::make($r->input('newpassword'));
                if($user->save()){
                    return Response()->json(['status' => true,
                        'data' => [],
                        'message' => $message->get(13,[
                        'use' => true,
                        'lang' => $user->language])
                    ]);
                }
                return Response()->json(['status' => false,
                    'data' => [],
                    'message' => $message->get(12,[
                    'use' => true,
                    'lang' => $user->language])
                ]);
            }
            return Response()->json(['status' => false,
                'data' => [],
                'message' => $message->get(2,[
                'use' => true,
                'lang' => $user->language])
            ]);
        }
        return Response()->json(['status' => false,
            'data' => [],
            'message' => $message->get(3,[
            'use' => true,
            'lang' => 'en'])
        ]);
    }

    public function UpdatePhotoProfile(Request $r){
        $message = new Message();
        $verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
        $user = $verify->first();
        if($user){
            // return Response()->json([
            //     'status' => false,
            //     'message'=> "Sory,Update Photo still disabled!"
            // ]);
            $image = $r->input('image');
            $profile = UserModel::where(['id' => $user->id])->first();
            $profile->photo = $image;
            if($profile->save()){
                return Response()->json([
                    'status' => true,
                    'photo' => $profile->photo,
                    'message' => $this->message->get(13,[
                        'use' => true,
                        'lang' => $user->language])]);
            }
            return Response()->json([
                'status' => false,
                'message' => $this->message->get(13,[
                    'use' => true,
                    'lang' => $user->language])]);
        }
        return Response()->json(['status' => false,
            'data' => [],
            'message' => $message->get(3,[
            'use' => true,
            'lang' => 'en'])
        ]);
    }

    public function registerAppId(Request $r){
        $message = new Message();
        $verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
        $user = $verify->first();
        if($user){
            $update = UserModel::where([
                'api_key' => $user->api_key,
                'secret_key' => $user->secret_key
            ])->first();

            $update->device_id = $r->input('deviceId');
            if($update->save()){
                return Response()->json([
                    'status' => true,
                    'message'=> "Register ID Success"
                ]);
            }
        }
        return Response()->json(['status' => false,
            'data' => [],
            'message' => $message->get(3,[
            'use' => true,
            'lang' => 'en'])
        ]);
    }

}
