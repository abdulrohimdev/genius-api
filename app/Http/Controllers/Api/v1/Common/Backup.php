<?php

namespace App\Http\Controllers\Api\v1\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Package\Backup\Backup as BackupData;
use Package\Common\Message; 
use Package\Common\VerifyAccount; 

class Backup extends Controller
{
    //
    public $verify;
    public $message;
    public function __construct(Request $r){
        $this->message = new Message();
        $this->verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
    }

    public function run(){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $backup = new BackupData();
            return Response()->json([
                'status' => true,
                'logs'   => $backup->run()
            ]);                
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);

    }
}
