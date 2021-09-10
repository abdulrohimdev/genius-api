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

use App\Models\HR\LetterManagement\CategoryUserAccess as CategoryAccessModel;

class CategoryUserAccess extends Controller
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
                $category_id = $r->input('category_id');
                $check  = CategoryAccessModel::where([
                    'secret_key' => $UserAccount->secret_key,
                    'type_id'=> $type_id,
                    'category_id'=> $category_id
                ])->count();
                if($check > 0){
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(22,[
                            'use' => true,
                            'lang' => $user->language])]);           
                }
                else{
                    $create = CategoryAccessModel::create([
                        'secret_key' => $UserAccount->secret_key,
                        'type_id'=> $type_id,
                        'category_id'=> $category_id
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
                    $data = DB::table('letter_user_categories')
                            ->leftJoin('type_of_letters','type_of_letters.id','letter_user_categories.type_id')
                            ->leftJoin('category_of_letters','category_of_letters.id','letter_user_categories.category_id')
                            ->leftJoin('users','users.secret_key','letter_user_categories.secret_key')
                            ->select('users.fullname','category_of_letters.category_letter','type_of_letters.type_of_letter','letter_user_categories.*')
                            ->where(['letter_user_categories.secret_key' => $secret_key])
                            ->where('category_of_letters.category_letter','LIKE','%'.$r->input('search').'%')
                            ->orWhere('type_of_letters.type_of_letter','LIKE','%'.$r->input('search').'%')
                            ->paginate(10);
                }
                else
                {
                    $data = DB::table('letter_user_categories')
                            ->leftJoin('type_of_letters','type_of_letters.id','letter_user_categories.type_id')
                            ->leftJoin('category_of_letters','category_of_letters.id','letter_user_categories.category_id')
                            ->leftJoin('users','users.secret_key','letter_user_categories.secret_key')
                            ->select('users.fullname','category_of_letters.category_letter','type_of_letters.type_of_letter','letter_user_categories.*')
                            ->where(['letter_user_categories.secret_key' => $secret_key])
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
                $delete = CategoryAccessModel::where('id',$id);
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

    public function GetAccessCategory(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $secret_key = $user->secret_key;
                if($r->input('search')){
                    $data = DB::table('letter_user_categories')
                    ->leftJoin('type_of_letters','type_of_letters.id','letter_user_categories.type_id')
                    ->leftJoin('category_of_letters','category_of_letters.id','letter_user_categories.category_id')
                    ->select('letter_user_categories.id','letter_user_categories.category_id','type_of_letters.type_of_letter','category_of_letters.category_letter')
                    ->where(['letter_user_categories.secret_key' => $secret_key,
                             'letter_user_categories.type_id' => $r->input('type_id')
                    ])
                    ->where('category_of_letters.category_letter','LIKE',"%".$r->input('search')."%")
                    ->paginate(5);
                }
                else{
                    $data = DB::table('letter_user_categories')
                    ->leftJoin('type_of_letters','type_of_letters.id','letter_user_categories.type_id')
                    ->leftJoin('category_of_letters','category_of_letters.id','letter_user_categories.category_id')
                    ->select('letter_user_categories.id','letter_user_categories.category_id','type_of_letters.type_of_letter','category_of_letters.category_letter')
                    ->where(['letter_user_categories.secret_key' => $secret_key,
                             'letter_user_categories.type_id' => $r->input('type_id')
                    ])
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
