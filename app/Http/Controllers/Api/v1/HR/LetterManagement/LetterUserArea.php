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

use App\Models\HR\LetterManagement\LetterUserArea as LetterUserAreaModel;

class LetterUserArea extends Controller
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
                $area_code = $r->input('area_code');
                $check  = LetterUserAreaModel::where([
                    'secret_key' => $UserAccount->secret_key,
                    'area_code'=> $area_code
                ])->count();
                if($check > 0){
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(22,[
                            'use' => true,
                            'lang' => $user->language])]);           
                }
                else{
                    $create = LetterUserAreaModel::create([
                        'secret_key' => $UserAccount->secret_key,
                        'area_code' => $area_code
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
                    $data = DB::table('letter_user_areas')
                            ->leftJoin('area_of_letters','area_of_letters.area_id','letter_user_areas.area_code')
                            ->leftJoin('users','users.secret_key','letter_user_areas.secret_key')
                            ->select('users.fullname','area_of_letters.area_name','letter_user_areas.*')
                            ->where(['letter_user_areas.secret_key' => $secret_key])
                            ->where('area_of_letters.area_id','LIKE','%'.$r->input('search').'%')
                            ->orWhere('area_of_letters.area_name','LIKE','%'.$r->input('search').'%')
                            ->paginate(10);
                }
                else{
                    $data = DB::table('letter_user_areas')
                            ->leftJoin('area_of_letters','area_of_letters.area_id','letter_user_areas.area_code')
                            ->leftJoin('users','users.secret_key','letter_user_areas.secret_key')
                            ->select('users.fullname','area_of_letters.area_name','letter_user_areas.*')
                            ->where(['letter_user_areas.secret_key' => $secret_key])
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
                $delete = LetterUserAreaModel::where('id',$id);
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
                $area = LetterUserAreaModel::where('id',$id)->first();
                $area->area_id = $r->input('area_id');
                $area->area_name = $r->input('area_name');
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

    public function GetAccessArea(Request $r){
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
                    $data = DB::table('letter_user_areas')
                    ->leftJoin('area_of_letters',
                                'area_of_letters.area_id',
                                'letter_user_areas.area_code')
                    ->select('letter_user_areas.*','area_of_letters.area_name')
                    ->where(['letter_user_areas.secret_key' => $secret_key])
                    ->where('area_of_letters.area_name','LIKE',"%".$r->input('search')."%")
                    ->orWhere('letter_user_areas.area_code','LIKE',"%".$r->input('search')."%")
                    ->paginate(5);
                }
                else{
                    $data = DB::table('letter_user_areas')
                    ->leftJoin('area_of_letters',
                                'area_of_letters.area_id',
                                'letter_user_areas.area_code')
                    ->select('letter_user_areas.*','area_of_letters.area_name')
                    ->where(['letter_user_areas.secret_key' => $secret_key])
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
