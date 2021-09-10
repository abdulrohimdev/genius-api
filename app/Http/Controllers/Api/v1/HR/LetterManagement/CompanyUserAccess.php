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

use App\Models\HR\LetterManagement\CompanyUserAccess as CompanyAccessModel;

class CompanyUserAccess extends Controller
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
                $company_code = $r->input('company_code');
                $check  = CompanyAccessModel::where([
                    'secret_key' => $UserAccount->secret_key,
                    'company_code'=> $company_code
                ])->count();
                if($check > 0){
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(22,[
                            'use' => true,
                            'lang' => $user->language])]);           
                }
                else{
                    $create = CompanyAccessModel::create([
                        'secret_key' => $UserAccount->secret_key,
                        'company_code' => $company_code
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
                    $data = DB::table('letter_user_companies')
                            ->leftJoin('companies','companies.company_code','letter_user_companies.company_code')
                            ->leftJoin('users','users.secret_key','letter_user_companies.secret_key')
                            ->select('users.fullname','companies.company_name','letter_user_companies.*')
                            ->where(['letter_user_companies.secret_key' => $secret_key])
                            ->where('companies.company_code','LIKE','%'.$r->input('search').'%')
                            ->orWhere('companies.company_name','LIKE','%'.$r->input('search').'%')
                            ->paginate(10);
                }
                else{
                    $data = DB::table('letter_user_companies')
                            ->leftJoin('companies','companies.company_code','letter_user_companies.company_code')
                            ->leftJoin('users','users.secret_key','letter_user_companies.secret_key')
                            ->select('users.fullname','companies.company_name','letter_user_companies.*')
                            ->where(['letter_user_companies.secret_key' => $secret_key])
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
                $delete = CompanyAccessModel::where('id',$id);
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
                $area = CompanyAccessModel::where('id',$id)->first();
                $area->company_code = $r->input('company_code');
                $area->company_name = $r->input('company_name');
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

    public function GetAccessCompany(Request $r){
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
                    $data = DB::table('letter_user_companies')
                    ->leftJoin('companies','companies.company_code','letter_user_companies.company_code')
                    ->select('letter_user_companies.*','companies.company_name')
                    ->where(['letter_user_companies.secret_key' => $secret_key])
                    ->where('companies.company_name','LIKE',"%".$r->input('search')."%")
                    ->orWhere('companies.company_code','LIKE',"%".$r->input('search')."%")
                    ->paginate(5);
                }
                else{
                    $data = DB::table('letter_user_companies')
                    ->leftJoin('companies','companies.company_code','letter_user_companies.company_code')
                    ->select('letter_user_companies.*','companies.company_name')
                    ->where(['secret_key' => $secret_key])
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
