<?php

namespace App\Http\Controllers\Api\v1\Common\Birthday;

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


class Birthdays extends Controller
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

    public function today(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $data = DB::table('employee_personals')
                        ->join('employee_memberships',function($join){
                            $join->on('employee_memberships.employee_id','=','employee_personals.employee_id');
                        })
                        ->join('employee_org_assignments','employee_org_assignments.employee_id','=','employee_personals.employee_id')
                        ->join('users','users.employee_id','=','employee_personals.employee_id')
                        ->join('companies','companies.company_code','=','users.company_code')
                        ->where('employee_memberships.terminate','!=','T')
                        ->whereDay('employee_personals.birthdate','=',date('d'))
                        ->whereMonth('employee_personals.birthdate','=',date('m'))
                        ->get();
                if($data){
                    return Response()->json([
                        'status' => true,
                        'data'  => $data
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


    public function birthdayEuclid(Request $r){
        $endpoint = "http://hris.dharmap.com/txn?fnc=runLib;opic=kMwKyTP7zw16NWbPMoLaMw;csn=P04;rc=XeK6Oco4vot6H0JjiqhI8muELKwEyRoM";
        $client = new \GuzzleHttp\Client();
        $response = $client->request("GET",$endpoint,[]);
        $data = \json_decode($response->getBody());
        $result = [];

        foreach($data as $item){
            if($item->company === 'DAC'){
                $company = 'PT. Dharma Polimetal';
            }
            else
            {
                $company = DB::table('companies')->where(['company_code' => $item->company])->first();
                $company = $company->company_name;
            }

            $user = UserModel::where(['employee_id' => $item->employee_id])->first();

            array_push($result,[
                'company_name' => $company,
                'divisi' => '',
		'fullname' => $item->fullname,
                'department' => $item->departemen,
		'photo' => $user->photo ?? null

            ]);
        }
        return Response()->json(['status' => true,'data' => $result],200,[],JSON_PRETTY_PRINT);



    }


}
