<?php

namespace App\Http\Controllers\Api\v1\HR\LetterManagement;

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

use App\Models\HR\LetterManagement\TypeUserAccess as TypeAccessModel;

class TypeUserAccess extends Controller
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
                $UserAccount = UserModel::where(['username' => $r->input('username')])->first();
                $type_id = $r->input('type_id');
                $check  = TypeAccessModel::where([
                    'secret_key' => $UserAccount->secret_key,
                    'type_id'=> $type_id
                ])->count();
                if($check > 0){
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(22,[
                            'use' => true,
                            'lang' => $user->language])]);
                }
                else{
                    $create = TypeAccessModel::create([
                        'secret_key' => $UserAccount->secret_key,
                        'type_id' => $type_id
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
                $UserAccount = UserModel::where(['username' => $r->input('username')])->first();
                $secret_key  = $UserAccount->secret_key;
                if($r->input('search')){
                    $data = DB::table('letter_user_types')
                            ->leftJoin('type_of_letters','type_of_letters.id','letter_user_types.type_id')
                            ->leftJoin('users','users.secret_key','letter_user_types.secret_key')
                            ->select('users.fullname','type_of_letters.type_of_letter','type_of_letters.code_of_type','letter_user_types.*')
                            ->where(['letter_user_types.secret_key' => $secret_key])
                            ->where('type_of_letters.code_of_type','LIKE','%'.$r->input('search').'%')
                            ->orWhere('type_of_letters.type_of_letter','LIKE','%'.$r->input('search').'%')
                            ->paginate(10);
                }
                else{
                    $data = DB::table('letter_user_types')
                            ->leftJoin('type_of_letters','type_of_letters.id','letter_user_types.type_id')
                            ->leftJoin('users','users.secret_key','letter_user_types.secret_key')
                            ->select('users.fullname','type_of_letters.type_of_letter','type_of_letters.code_of_type','letter_user_types.*')
                            ->where(['letter_user_types.secret_key' => $secret_key])
                            ->paginate(10);
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
                $delete = TypeAccessModel::where('id',$id);
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
                $area = TypeAccessModel::where('id',$id)->first();
                $area->type_id = $r->input('type_id');
                $area->type_of_letter = $r->input('type_of_letter');
                if($area->update()){
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

    public function GetAccessType(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $username = $r->input('username');
                if($username){
                    $UserAccount = UserModel::where(['username' => $username])->first();
                    $secret_key = $UserAccount->secret_key;
                }
                else{
                    $secret_key = $user->secret_key;
                }
                if($r->input('search')){
                    $data = DB::table('letter_user_types')
                    ->leftJoin('type_of_letters',
                                'type_of_letters.id',
                                'letter_user_types.type_id')
                    ->select('letter_user_types.*','type_of_letters.type_of_letter','type_of_letters.code_of_type')
                    ->where(['letter_user_types.secret_key' => $secret_key])
                    ->where('type_of_letters.type_of_letter','LIKE',"%".$r->input('search')."%")
                    ->paginate(5);
                }
                else{
                    $data = DB::table('letter_user_types')
                    ->leftJoin('type_of_letters',
                                'type_of_letters.id',
                                'letter_user_types.type_id')
                    ->select('letter_user_types.*','type_of_letters.type_of_letter','type_of_letters.code_of_type')
                    ->where(['letter_user_types.secret_key' => $secret_key])
                    ->paginate(5);
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


}
