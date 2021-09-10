<?php

namespace App\Http\Controllers\Api\v1\HR\ApprovalList;

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

use App\Models\HR\ApprovalList\ApprovalList as ApprovalModel;

class ApprovalList extends Controller
{
    //
    public $message;
    public $verify;

    function __construct(Request $r){
        $this->message = new Message();
        $this->verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
    }
	


    public function GetList(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $personal = DB::table("employee_org_assignments")
                                   ->where([
                                        'employee_id' => $user->employee_id,
                                   ])
                                   ->first();
				

                // $ApprovalList    = ApprovalModel::where([
                //     'divisi' => $personal ->divisi,
                //     'company'=> $personal ->company_code
                // ])->get();
		
		$position = $personal->position_code;
		/*
		$headList = DB::select(DB::raw("
 		  SELECT a.*,e.position_code,u.photo
			FROM approval_lists a
			JOIN employee_org_assignments e ON e.employee_id=a.empid
			JOIN users u ON u.employee_id=a.empid
			WHERE e.position_code NOT IN('GH','UH','SF','SH','".$position."') and
			a.divisi='".$personal->divisi."' and a.company='".$personal->company_code."'
		"));
		*/	
		
		$withDivisi = "and e.divisi='".$personal->divisi."'";

		if($position === 'DH'){
		  $withDivisi = "";
		}
	
		$headList = DB::select(DB::raw("
			SELECT e.employee_id AS empid,u.fullname,e.divisi, e.department,u.photo,e.company_code AS company,e.position_code
				FROM employee_org_assignments e
			JOIN users u ON u.employee_id=e.employee_id
			WHERE e.position_code NOT IN('DRV','OP','PKL','PKL1','ADM','TA','TS','TK','IK','MGN','KTR','GH','UH','SF','SH','".$position."') $withDivisi and e.company_code='".$personal->company_code."'
		
		"));

                $data = [];
                foreach($headList as $item){
                    array_push($data,[
                        'name' => $item->fullname,
                        'empid'=> $item->empid,
                        'image'=> $item->photo,
                    ]);
                }

                return Response()->json([
                    'status' => true,
                    'data'   => $data
                ]);

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

    public function CreateLeave(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $data = $r->all();
                $date = date('Y-m-d',strtotime($data['date']));
                $now  = date('Y-m-d');
                $datetime1 = new \DateTime(($now));
                $datetime2 = new \DateTime(($date));
                $interval = $datetime1->diff($datetime2);
                $realDiff = $interval->format('%R%a');
                if($realDiff >= 0){
                    return Response()->json([
                        'status' => true,
                        'message'=> $date,
                    ]);
                }
                else{
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(26,[
                            'use' => true,
                            'lang' => $user->language])]);
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
