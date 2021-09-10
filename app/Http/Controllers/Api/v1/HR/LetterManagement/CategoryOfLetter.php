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

use App\Models\HR\LetterManagement\TypeOfLetter as TypeModel;
use App\Models\HR\LetterManagement\CategoryOfLetter as CategoryModel;

class CategoryOfLetter extends Controller
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

    public function store(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $type_of_letter_id = $r->input('type_of_letter_id');
                $category_letter = $r->input('category_letter');
                $create = CategoryModel::create([
                    'type_of_letter_id' => $type_of_letter_id,
                    'category_letter' => $category_letter
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
                if($r->input('search')){
                    // $data = CategoryModel::where('category_letter','LIKE','%'.$r->input('search').'%')
                    //         ->paginate(10);
                    $data = DB::table('category_of_letters')
                            ->leftJoin('type_of_letters','category_of_letters.type_of_letter_id','type_of_letters.id')
                            ->select('category_of_letters.*','type_of_letters.type_of_letter')
                            ->where('category_of_letters.category_letter','LIKE','%'.$r->input('search').'%')
                            ->paginate(10);
                }
                else{
                    // $data = CategoryModel::paginate(10);
                    $data = DB::table('category_of_letters')
                            ->leftJoin('type_of_letters','category_of_letters.type_of_letter_id','type_of_letters.id')
                            ->select('category_of_letters.*','type_of_letters.type_of_letter')
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

    public function DataListByType(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $typeid = $r->input('typeid');
                if($r->input('search')){
                    $data = DB::table('category_of_letters')
                            ->leftJoin('type_of_letters','category_of_letters.type_of_letter_id','type_of_letters.id')
                            ->select('category_of_letters.*','type_of_letters.type_of_letter')
                            ->where('category_of_letters.type_of_letter_id',$typeid)
                            ->where('category_of_letters.category_letter','LIKE','%'.$r->input('search').'%')
                            ->paginate(10);
                }
                else{
                    $data = DB::table('category_of_letters')
                            ->leftJoin('type_of_letters','category_of_letters.type_of_letter_id','type_of_letters.id')
                            ->select('category_of_letters.*','type_of_letters.type_of_letter')
                            ->where('category_of_letters.type_of_letter_id',$typeid)
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
                $delete = CategoryModel::where('id',$id);
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
                $category = CategoryModel::where('id',$id)->first();
                $category->type_of_letter_id = $r->input('type_of_letter_id');
                $category->category_letter = $r->input('category_letter');
                if($category->update()){
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

    public function GetCategory(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $typeletter_id = $r->input('typeletter_id');
                $data = CategoryModel::whereIn('type_of_letter_id',[$typeletter_id])->get();
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
