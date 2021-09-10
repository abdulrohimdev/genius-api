<?php

namespace App\Http\Controllers\Api\v1\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Common\ApplicationModel;
use App\Models\Common\RoleModel;
use App\Models\Common\RoleGroupModel;
use App\Models\Common\ApplicationRuleModel;
use Illuminate\Support\Facades\Hash;
use Package\Common\Message;
use Package\Common\VerifyAccount;


class Application extends Controller
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

    public function List(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $application = RoleGroupModel::whereIn('role_code_id',[$r->input('role_code')])->get();
            return Response()->json([
                'status' => true,
                'data'   => $application,
                'message' => '']);       
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);   
    }

    public function Rule(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $application = ApplicationRuleModel::whereIn('application',[$r->input('application')])->get();
            return Response()->json([
                'status' => true,
                'data'   => $application,
                'message' => '']);       
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);   
    }

}
