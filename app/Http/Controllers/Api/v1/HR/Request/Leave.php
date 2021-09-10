<?php

namespace App\Http\Controllers\Api\v1\HR\Request;

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

use App\Models\HR\Request\Leave as LeaveModel;

class Leave extends Controller
{
    public $message;
    public $verify;
    public $socket;
    function __construct(Request $r){
        date_default_timezone_set('Asia/Jakarta');
        $this->message = new Message();
        $this->verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
        $this->socket = new Socket([
            'host' => 'http://147.139.175.101',
            'port' => 6001,
            'key'  => 'xkejhkakjgagsaksjgkajsgkajsgkasjkasdkassjgkasjgkajskcmsakjgkasjgakdksajfakj'
        ]);
    }


    public function Unix($n) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    public function test(Request $r){
        $fcm = new FCM();
        echo $fcm->sendPushNotification("dNwPDh5URFCI6r4-zzRrOY:APA91bGUuHDodNC-H_sBVTJaeWQfu9p859moDZ_Rot7mxEY8VSojbzzYtpDXFRlTVuIEEcVsa30Wc40Df26TMIgAP_BrXN70lsnUUl5vi8r-HeqVAsAMhs48QJqcnRttZWjLscOdL4Zo","via PHP","Trials");
    }

    public function LeaveCanceled(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $hash_id = $r->input('request_hash_id');
                $approver_id = $r->input('approver_id');
                $fullname = $r->input('fullname');
                $approver = UserModel::where(['employee_id' => $approver_id])->first();
                $leaveDelete = LeaveModel::where([
                    'request_hash_id' => $hash_id
                ])->delete();

                if($leaveDelete){
                    $fcm = new FCM();
                    $fcm->sendPushNotification($approver->device_id,"Pembatalan izin keluar",$fullname. ' telah membatalkan permintaan untuk izin keluar',[
                        "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                        "sound" => "default",
                        "status" => "done",
                        "id"     => 1,
                        'hash_id'=> $hash_id,
                        "screen" => "/home",
                        'arguments'=> $hash_id
                    ]);
                    return Response()->json([
                        'status' => true,
                        'message' => $this->message->get(31,[
                            'use' => true,
                            'lang' => $user->language])]);
                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(32,[
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

    public function UpdateLeave(Request $r){
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
                try{
                    $data_insert = [
                        'request_type' => $r->input('leave_type'),
                        'request_approval' => $r->input('approval'),
                        'request_date' => $date,
                        'request_time_leaving' => $r->input('leaving'),
                        'request_time_returning' => $r->input('returning'),
                        'request_reason' => $r->input('reason'),
                    ];

                    $leave = LeaveModel::where(['number_unix' => $r->input('number_unix')])->first();
                    $leave->request_type = $r->input('leave_type');
                    $leave->request_approval = $r->input('approval');
                    $leave->request_date = $date;
                    $leave->request_time_leaving = $r->input('leaving');
                    $leave->request_time_returning = $r->input('returning');
                    $leave->request_reason = $r->input('reason');
                    if($leave->update()){
                        $to = UserModel::where(['employee_id' => $r->input('approval')])->first();
                        $fcm = new FCM();
                        $fcm->sendPushNotification($to->device_id,"U",$user->fullname. ' membutuhkan approval dari anda untuk keperluan '.$r->input('leave_type'),[
                            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                            "sound" => "default",
                            "status" => "done",
                            "id"     => $leave->id,
                            'hash_id'=> $leave->request_hash_id,
                            "screen" => "/request_leave_detail",
                            'arguments'=> $leave->request_hash_id
                        ]);
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
                catch(\Exception $e){
                    return Response()->json([
                        'status' => false,
                        'message' => $e->getMessage()]);
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

    public function CreateLeaveV2(Request $r){
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
                    try{
                        $data_insert = [
                            'request_type' => $r->input('leave_type'),
                            'request_hash_id' => Hash::make(uniqid()),
                            'number_unix' => $this->Unix(8),
                            'request_user_id' => $user->secret_key,
                            'request_user_empid' => $user->employee_id,
                            'request_approval' => $r->input('approval'),
                            'request_date' => $date,
                            'request_time_leaving' => $r->input('leaving'),
                            'request_time_returning' => $r->input('returning'),
                            'request_reason' => $r->input('reason'),
                            'status' => 'Pending',
                        ];
                        $create = LeaveModel::create($data_insert);
                        if($create){
                        $to = UserModel::where(['employee_id' => $r->input('approval')])->first();
                        $fcm = new FCM();
                        $fcm->sendPushNotification($to->device_id,"Menunggu Approval",$user->fullname. ' membutuhkan approval dari anda untuk keperluan '.$r->input('leave_type'),[
                            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                            "sound" => "default",
                            "status" => "done",
                            "id"     => $create->id,
                            'hash_id'=> $create->request_hash_id,
                            "screen" => "/request_leave_detail",
                            'arguments'=> $create->request_hash_id
                        ]);

                        return Response()->json([
                                'status' => true,
                                'message' => $this->message->get(27,[
                                    'use' => true,
                                    'lang' => $user->language])]);
                        }
                        return Response()->json([
                            'status' => false,
                            'message' => $this->message->get(28,[
                                'use' => true,
                                'lang' => $user->language])]);
                    }
                    catch(\Exception $e){
                        return Response()->json([
                            'status' => false,
                            'message' => $e->getMessage()]);
                    }
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
                    try{
                        $data_insert = [
                            'request_type' => $r->input('leave_type'),
                            'request_hash_id' => Hash::make(uniqid()),
                            'number_unix' => $this->Unix(8),
                            'request_user_id' => $user->secret_key,
                            'request_user_empid' => $user->employee_id,
                            'request_approval' => $r->input('approval'),
                            'request_date' => $date,
                            'request_time_leaving' => $r->input('time_in'),
                            'request_time_returning' => $r->input('time_out'),
                            'request_reason' => $r->input('reason'),
                            'status' => 'Pending',
                        ];
                        $create = LeaveModel::create($data_insert);
                        if($create){
                        $to = UserModel::where(['employee_id' => $r->input('approval')])->first();
                        $fcm = new FCM();
                        $fcm->sendPushNotification($to->device_id,"Menunggu Approval",$user->fullname. ' membutuhkan approval dari anda untuk keperluan '.$r->input('leave_type'),[
                            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                            "sound" => "default",
                            "status" => "done",
                            "id"     => $create->id,
                            'hash_id'=> $create->request_hash_id,
                            "screen" => "/request_leave_detail",
                            'arguments'=> $create->request_hash_id
                        ]);

                        return Response()->json([
                                'status' => true,
                                'message' => $this->message->get(27,[
                                    'use' => true,
                                    'lang' => $user->language])]);
                        }
                        return Response()->json([
                            'status' => false,
                            'message' => $this->message->get(28,[
                                'use' => true,
                                'lang' => $user->language])]);
                    }
                    catch(\Exception $e){
                        return Response()->json([
                            'status' => false,
                            'message' => $e->getMessage()]);
                    }
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

    public function ListLeave(Request $r){
        $user = $this->verify->first();
        if($user){
            $List = LeaveModel::where([
                'request_user_id' => $user->secret_key,
            ])->orderBy('id','desc')->get();
            return Response()->json([
                'status' => true,
                'data'   => $List
            ]);
        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);
    }

    public function DeniedOrAccept(Request $r){
        $user = $this->verify->first();
        if($user){
            $data = LeaveModel::where([
                'request_hash_id' => $r->input('request_hash_id'),
            ])->first();
            $status = $r->input('status');
	    $data->status = $status;		

            $data->photo_approval = $r->input('image');
            if($data->save()){
            $to = UserModel::where(['employee_id' => $data->request_user_empid])->first();
            $fcm = new FCM();
            $isAction =[
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "sound" => "default",
                "status" => "done",
                "id"     => $data->id,
                'hash_id'=> $data->request_hash_id,
                "screen" => "/request_leave_detail",
                'arguments'=> $data->request_hash_id];
            if($data->status === 'Rejected'){
                $status = "Ditolak";
                $fcm->sendPushNotification($to->device_id,"Mohon maaf","Permintaan izin keluar perusahaan anda telah di ".$status,$isAction);
            }
            else if($data->status === 'Approved'){
                $status = 'Disetujui';
                $fcm->sendPushNotification($to->device_id,"Selamat!!","Permintaan izin keluar perusahaan anda telah di ".$status,$isAction);
            }
            return Response()->json([
                    'status' => true,
                    'message' => $this->message->get(29,[
                                'use' => true,
                                'lang' => 'en'])]);

            }

            return Response()->json([
                'status' => false,
                'message' => $this->message->get(30,[
                            'use' => true,
                            'lang' => 'en'])]);
        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);
    }

    public function LeaveDetail(Request $r){
        $user = $this->verify->first();
        if($user){
            $id = $r->input('request_hash_id');
            $List = DB::select("
            select ul.number_unix,ul.request_type,ul.request_time_leaving,
            ul.request_time_returning,ul.request_reason,ul.request_date,
            ul.security_check_leave,ul.security_check_return,ul.status,
            u.fullname as user_fullname,u2.fullname as approval, ul.request_approval
            from user_request_leaves ul
            join users u ON u.secret_key=ul.request_user_id
            join users u2 ON u2.employee_id=ul.request_approval
            where ul.request_hash_id='$id'
            ")[0];
            return Response()->json([
                'status' => true,
                'data'   => $List
            ]);

        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);
    }


    public function LeaveListRequest(Request $r){
        $user = $this->verify->first();
        if($user){
            $approved_by = $r->input('approved_by');
            $now = date('Y-m-d');
            $data = DB::select(
                "select u.fullname,ul.* from user_request_leaves ul
                 join users u ON u.employee_id=ul.request_user_empid
                 where ul.request_approval='$approved_by'
                 order by ul.id desc,ul.status asc
                "
            );

            $message = [];
            foreach($data as $item){
                array_push($message,[
                    'id'  => $item->id,
                    'request_hash_id' => $item->request_hash_id,
                    'name' => $item->fullname." - ".$item->created_at,
                    'message' => $item->fullname." menunggu approval dari anda",
                    'status' => $item->status
                ]);
            }

            return Response()->json([
                'status' => true,
                'data'   => $message
            ]);
        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);

    }

    public function getNotificationPending(Request $r){
        $user = $this->verify->first();
        if($user){
            $now = date('Y-m-d');
            $data = LeaveModel::where(['status' => 'Pending', 'request_approval' => $user->employee_id]);
            return Response()->json([
                'status' => true,
                'data'   => $data->count()
            ]);

        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);

    }

    public function searchByEmployeeID(Request $r){
        $user = $this->verify->first();
        if($user){
            $employee_id = $r->input('employee_id');
            $now = date('Y-m-d');
            $data = DB::select("
                select u.photo,u.fullname,u2.fullname as approval, ul.*,org.divisi,org.department from user_request_leaves ul
                JOIN users u ON u.employee_id=ul.request_user_empid
                JOIN employee_org_assignments org ON org.employee_id=ul.request_user_empid
                JOIN users u2 ON u2.employee_id=ul.request_approval
                where (ul.request_user_empid like '%$employee_id%' OR u.fullname like '%$employee_id%') and request_date='$now'
                and ul.status='Approved' order by ul.id desc
            ");
            // $data = LeaveModel::where(['request_user_empid' => $employee_id,'request_date' => $now])->get();
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

   
    public function searchByFromDate(Request $r){
        $user = $this->verify->first();
        if($user){
            $search = $r->input('search');
	    $from   = date('Y-m-d',strtotime($r->input('from')));
	    $end    = date('Y-m-d',strtotime($r->input('end')));

            $now = date('Y-m-d');
            $data = DB::select("
                select u.photo,u.fullname,u2.fullname as approval, ul.*,org.divisi,org.department from user_request_leaves ul
                JOIN users u ON u.employee_id=ul.request_user_empid
                JOIN employee_org_assignments org ON org.employee_id=ul.request_user_empid
                JOIN users u2 ON u2.employee_id=ul.request_approval
                where (ul.request_user_empid like '%$search%' OR u.fullname like '%$search%') and (request_date between '".$from."' and '".$end ."')
                and ul.status='Approved'
            ");

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


    public function searchByUnixNumber(Request $r){
        $user = $this->verify->first();
        if($user){
            $number_unix = $r->input('number_unix');
            $data = DB::select("
                select u.photo,u.fullname,u2.fullname as approval, ul.*,org.divisi,org.department from user_request_leaves ul
                JOIN users u ON u.employee_id=ul.request_user_empid
                JOIN employee_org_assignments org ON org.employee_id=ul.request_user_empid
                JOIN users u2 ON u2.employee_id=ul.request_approval
                where ul.number_unix like '%$number_unix%'
            ");
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

    public function securityAction(Request $r){
        $user = $this->verify->first();
        if($user){
            $number_unix = $r->input('number_unix');
            $leave = LeaveModel::where(['number_unix' => $number_unix])->first();
            if($leave->security_check_leave !== null && $leave->security_check_return == null){
                $leave->security_check_return = date('H:i');
                $leave->save();
            }

            if($leave->security_check_leave == null){
                $leave->security_check_leave = date('H:i');
                $leave->save();
            }
            $getLeave = LeaveModel::where(['number_unix' => $number_unix])->first();
            $user_data= UserModel::where(['employee_id' => $getLeave->request_user_empid])->first();
            $fcm = new FCM();
            $isAction =[
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "sound" => "default",
                "status" => "done",
                "id"     => $getLeave->id,
                'hash_id'=> $getLeave->request_hash_id,
                "screen" => "/request_leave_detail",
                'arguments'=> $getLeave->request_hash_id];
            $fcm->sendPushNotification($user_data->device_id,"Pengecekan security","Terimakasih anda sudah di cek oleh security",$isAction);
            return Response()->json([
                'status' => true,
                'data'   => $getLeave
            ]);
        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);
    }

    public function LeaveRecord(Request $r){
        $user = $this->verify->first();
        if($user){
            $company = $r->input('company');
            $fromdate = $r->input('fromdate');
            $todate = $r->input('todate');
            $show_limit = (int) $r->input('limit');
            $data = DB::table('user_request_leaves')
                    ->join('users','users.employee_id','=','user_request_leaves.request_user_empid')
                    ->join('users as approval','approval.employee_id','=','user_request_leaves.request_approval')
                    ->join('employee_org_assignments as org',function($join){
                        $join->on('org.employee_id','=','user_request_leaves.request_user_empid');
                        $join->on('org.company_code','=','users.company_code');
                    })
                    ->select('users.*','user_request_leaves.*','approval.fullname as is_approval_name','org.*')
                    ->whereBetween('user_request_leaves.request_date',[$fromdate,$todate])
                    ->where('users.company_code',$company)
                    ->orderBy('user_request_leaves.id','desc')
                    ->paginate($show_limit);
            return Response()->json([
                'status' => true,
                'data'   => $data,
                'message' => ''
            ]);

        }
        return Response()->json([
                'status' => false,
                'message' => $this->message->get(3,[
                            'use' => true,
                            'lang' => 'en'])]);
    }


}
