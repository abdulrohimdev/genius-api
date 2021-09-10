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
use Package\Letters\Letter;
use Carbon\Carbon;
use DB;

use App\Models\HR\LetterManagement\CompanyLetter as CompanyLetterModel;
use App\Models\HR\LetterManagement\Confidential;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CompanyLetter extends Controller
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
                $file = $r->file('file');
                $fname= "";
                if($file){
                    $ext = $file->extension();
                    $fname = Str::random(200).".".$ext;
                    $destination = public_path("storage/Letter");
                    $isUpload= $file->move($destination,$fname);    
                }
                
                $data = [
                    'company_code' => $r->input('company_code'),
                    'department_code' => $r->input('department_code'),
                    'type_id' => $r->input('type_id'),
                    'category_id' => $r->input('category_id'),
                    'area_code' => $r->input('area_code'),
                    'title' => $r->input('title'),
                    'upload_path_document' => $fname,
                    'confidential' => $r->input('confidential')
                ];

                $letter = new Letter();
                if($r->input('countletter')){
                    $create = $letter->CreateNumber($data,
                        $r->input('countletter'),
                        $user->username
                    );
                }
                if($create['status'] == true){
                    return Response()->json([
                        'status' => true,
                        'message' => $this->message->get(16,[
                            'use' => true,
                            'lang' => $user->language]),
                        'data' => $create,
                    ],200,[],JSON_PRETTY_PRINT);                        
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
                $secret_key = $user->secret_key;
                $letter = new Letter();
                $letter = $letter->LetterAccessList($secret_key,$r->input('search'));
                $confidential = Confidential::where(['username' => $user->username])->first();
                if($confidential){
                   $confidential = $confidential->confidential; 
                }
                else{
                    $confidential = "N";
                }
                return Response()->json([
                    'status' => true,
                    'data' => $letter,
                    'username' => $user->username,
                    'confidential' => $confidential
                ],200,[],JSON_PRETTY_PRINT); 
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
                $doc_number = $r->input('doc_number');
                $old_filename = $r->input('old_filename');
                $file = $r->file('file');
                $data = CompanyLetterModel::where('doc_number',$doc_number);
                if($file){
                    $ext = $file->extension();
                    $fname = Str::random(200).".".$ext;
                    $destination = public_path("storage/Letter");
                    $isUpload= $file->move($destination,$fname);    
                    if($data->update(['title' => $r->input('title'),'upload_path_document' => $fname])){
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
                else{
                    if($data->update(
                        ['title' => $r->input('title')])
                    ){
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

    public function Download(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $filename = $r->input('filename');
                if($filename){
                    $path = public_path("storage/Letter/".$filename);
                    $check = File::exists($path);
                    if($check){
                        return Response()->download($path);
                    }    
                    return Response()->json([
                        'status' => false,
                        'message' => "File Not Found"]);                            
                }
                return Response()->json([
                    'status' => false,
                    'message' => "File Not Found"]);                        
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

    public function GetCountAndLetterNumber(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $document_number = $r->input('doc_number');
                $count = DB::table('company_letters')->whereIn('doc_number',[$document_number])->count();
                $first_number = DB::table('company_letters')
                                ->where(['doc_number' => $document_number])
                                ->orderBy('id','asc')
                                ->first();
                $last_number = DB::table('company_letters')
                                ->where(['doc_number' => $document_number])
                                ->orderBy('id','desc')
                                ->first();
                $data        = DB::table('company_letters')
                                ->whereIn('doc_number',[$document_number])
                                ->get();
                return Response()->json([
                    'status' => true,
                    'count' => $count,
                    'first_number' => $first_number->number_of_letter,
                    'last_number'  => $last_number->number_of_letter,
                    'data' => $data
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

}
