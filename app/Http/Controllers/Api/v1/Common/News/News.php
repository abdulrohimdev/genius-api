<?php

namespace App\Http\Controllers\Api\v1\Common\News;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Common\UserModel;
use App\Models\Common\UserRoleModel;

use Package\Common\Message;
use Package\Common\Socket;
use Package\Common\VerifyAccount;
use Package\Common\FCM;
use Carbon\Carbon;
use DB;

use App\Models\Common\News\News as NewsModel;

class News extends Controller
{
    public $message;
    public $verify;
    function __construct(Request $r){
        date_default_timezone_set('Asia/Jakarta');
        $this->message = new Message();
        $this->verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
    }


    public function getNews(){
        $user = $this->verify->first();
        if($user){
            try{
                $company = $user->company_code;
                $now = date('Y-m-d');
                $news = NewsModel::where('filter_company','like','%'. $company .'%')
                        ->whereDate('posted_date','>=',$now)
                        ->select('title','subtitle','description','image_uri','posted_date','posted_by','created_at')
                        ->orderBy('id','desc')
                        ->get();
                if($news){
                    return Response()->json([
                        'status' => true,
                        'message' => '',
                        'data'  => $news
                    ]);
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
