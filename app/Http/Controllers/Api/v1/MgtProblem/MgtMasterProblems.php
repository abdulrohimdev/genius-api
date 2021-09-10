<?php

namespace App\Http\Controllers\Api\v1\MgtProblem;

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

use App\Models\MgtProblems\MasterProblem;


class MgtMasterProblems extends Controller
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

    public function GetLocation(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $area     = "Quality";
            $company  = $user->company_code;
            if($r->input('search')){
                $location = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area
                ])
                ->where('location','LIKE','%'.$r->input('search').'%')
                ->select('location')->distinct()->get();
            }
            else{
                $location = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area
                ])->select('location')->distinct()->get();
            }
            return Response()->json([
                'status' => true,
                'data'   => $location,
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function GetLineProcess(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $area     = "Quality";
            $company  = $user->company_code;
            if($r->input('search')){
                $location = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area,
                    'location' => $r->input('location')
                ])
                ->where('process','LIKE','%'.$r->input('search').'%')
                ->select('process')->distinct()->get();
            }
            else{
                $location = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area,
                    'location' => $r->input('location')
                ])->select('process')->distinct()->get();
            }
            return Response()->json([
                'status' => true,
                'data'   => $location,
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function GetType(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $area     = "Quality";
            $company  = $user->company_code;
            $location = $r->input('location');
            $process = $r->input('process');
            if($r->input('search')){
                $data = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area,
                    'location' => $location,
                    'process' => $process,
                ])
                ->where('type','LIKE','%'.$r->input('search').'%')
                ->select('type')->distinct()->get();
            }
            else{
                $data = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area,
                    'location' => $location,
                    'process' => $process,
                ])->select('type')->distinct()->get();
            }
            return Response()->json([
                'status' => true,
                'data'   => $data
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function GetProduct(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $area     = "Quality";
            $company  = $user->company_code;
            $location = $r->input('location');
            $process = $r->input('process');
            $type = $r->input('type');
            if($r->input('search')){
                $data = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area,
                    'location' => $location,
                    'process' => $process,
                    'type' => $type,
                ])
                ->where('product','LIKE','%'.$r->input('search').'%')
                ->select('product')->distinct()->get();
            }
            else{
                $data = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area,
                    'location' => $location,
                    'process' => $process,
                    'type' => $type,
                ])
                ->select('product')->distinct()->get();
            }
            return Response()->json([
                'status' => true,
                'data'   => $data
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }


    public function GetProblem(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $area     = "Quality";
            $company  = $user->company_code;
            $location = $r->input('location');
            $process = $r->input('process');
            $type = $r->input('type');
            $case_type = $r->input('case_type');
            $product = $r->input('product');
            if($r->input('search')){
                $data = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area,
                    'location' => $location,
                    'process' => $process,
                    'type' => $type,
                    'case_type' => $case_type,
                    'product' => $product,
                ])
                ->where('problem','LIKE','%'.$r->input('search').'%')
                ->select('problem')->distinct()->get();

            }
            else{
                $data = MasterProblem::where([
                    'company_code' => $company,
                    'management_area' => $area,
                    'location' => $location,
                    'process' => $process,
                    'type' => $type,
                    'case_type' => $case_type,
                    'product' => $product,
                ])->select('problem')->distinct()->get();
            }
            return Response()->json([
                'status' => true,
                'data'   => $data
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }


}
