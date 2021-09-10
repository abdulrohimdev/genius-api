<?php
/* 
    Author by Abdul Rohim
    Library Code Message
*/
namespace Package\Letters;

use App\Models\HR\LetterManagement\TypeOfLetter as TypeModel;
use App\Models\HR\LetterManagement\CategoryOfLetter as CategoryModel;
use App\Models\HR\LetterManagement\CompanyLetter as CompanyLetterModel;
use Illuminate\Support\Carbon;

use App\Models\HR\LetterManagement\TypeUserAccess as TypeAccess;
use App\Models\HR\LetterManagement\CategoryUserAccess as CategoryAccess;
use App\Models\HR\LetterManagement\CompanyUserAccess as CompanyAccess;
use App\Models\HR\LetterManagement\LetterUserArea as AreaAccess;
use App\Models\HR\LetterManagement\DepartmentUserAccess as DepartmentAccess;
use DB;

use Package\Common\DocType as DoctypePackage;

class Letter{

    public function Romawi($month){
        $romawi = [
            '01' => "I","02" => "II","03" => "III","04" => "IV",
            '05' => "V","06" => "VI","07" => "VII","08" => "VIII",
            '09' => "IX","10" => "X","11" => "XI","12" => "XII",
        ];
        return $romawi[$month];
    }

    public function LetterAccessList($secret_key,$search=false){
        $TypeAccess = TypeAccess::where(['secret_key' => $secret_key])->select('type_id')->get();
        $CtgAccess  = CategoryAccess::where(['secret_key' => $secret_key])->select('category_id')->get();
        $CompAccess = CompanyAccess::where(['secret_key' => $secret_key])->select('company_code')->get();
        $DeptAccess = DepartmentAccess::where(['secret_key' => $secret_key])->select('department_code')->get();
        $AreaAccess = AreaAccess::where(['secret_key' => $secret_key])->select('area_code')->get();
        $TypeAccessList = [];
        foreach($TypeAccess as $item){
            $TypeAccessList[] = $item->type_id;
        }
        $CtgAccessList = [];
        foreach($CtgAccess as $item){
            $CtgAccessList[] = $item->category_id;
        }
        $CompAccessList = [];
        foreach($CompAccess as $item){
            $CompAccessList[] = $item->company_code;
        }

        $DeptAccessList = [];
        foreach($DeptAccess as $item){
            $DeptAccessList[] = $item->department_code;
        }
     
        $AreaAccessList = [];
        foreach($AreaAccess as $item){
            $AreaAccessList[] = $item->area_code;
        }
        if($search){
            $List = DB::table('company_letters')
                    ->leftJoin('category_of_letters','category_of_letters.id','company_letters.category_id')
                    ->leftJoin('type_of_letters','type_of_letters.id','company_letters.type_id')
                    ->leftJoin('approval_document_status','approval_document_status.doc_number','company_letters.doc_number')
                    ->select('type_of_letters.type_of_letter','category_of_letters.category_letter',
                                'company_letters.*',
                                'approval_document_status.create_by',
                                'approval_document_status.status',
                                'approval_document_status.approval_note',
                                'approval_document_status.approved_by',
                            )
                    ->whereIn('company_letters.company_code',$CompAccessList)
                    ->whereIn('company_letters.department_code',$DeptAccessList)
                    ->whereIn('company_letters.type_id',$TypeAccessList)
                    ->whereIn('company_letters.category_id',$CtgAccessList)
                    ->whereIn('company_letters.area_code',$AreaAccessList)
                    ->where('company_letters.title','LIKE','%'.$search.'%')
                    ->orWhere('company_letters.number_of_letter','LIKE','%'.$search.'%')
                    ->orWhere('company_letters.company_code','LIKE','%'.$search.'%')
                    ->orderBy('id','desc')
                    ->paginate(10);
        }
        else
        {
            $List = DB::table('company_letters')
            ->leftJoin('category_of_letters','category_of_letters.id','company_letters.category_id')
            ->leftJoin('type_of_letters','type_of_letters.id','company_letters.type_id')
            ->leftJoin('approval_document_status','approval_document_status.doc_number','company_letters.doc_number')
            ->select('type_of_letters.type_of_letter','category_of_letters.category_letter',
                        'company_letters.*',
                        'approval_document_status.create_by',
                        'approval_document_status.status',
                        'approval_document_status.approval_note',
                        'approval_document_status.approved_by',
                    )
            ->whereIn('company_letters.company_code',$CompAccessList)
            ->whereIn('company_letters.department_code',$DeptAccessList)
            ->whereIn('company_letters.type_id',$TypeAccessList)
            ->whereIn('company_letters.category_id',$CtgAccessList)
            ->whereIn('company_letters.area_code',$AreaAccessList)
            ->orderBy('id','desc')
            ->paginate(10);
        }
        return $List;
    }

    public function CreateNumber($data,$count,$createby){
        date_default_timezone_set('Asia/Jakarta');
        $package = new DoctypePackage();
        $GetType = TypeModel::where(['id' => $data['type_id']])->first();
        $GetCategory = CategoryModel::where(['id' => $data['category_id']])->first();
        $doc_number = $package->CreateDocNumber("LETTER",$createby,"APPROVED");
        $CompanyLetter = CompanyLetterModel::where([
            'company_code' => $data['company_code'],
            'type_id' => $GetType->id,
        ])->orderBy('id','desc')->first();

        $theNumber = null;

        if($CompanyLetter){
            $LatestNumber1 = $CompanyLetter->number_of_letter;
            $LatestNumber2 = explode("/",$LatestNumber1);
            $LatestNumber = (int) $LatestNumber2[0];
            $Year         = date('Y',strtotime($CompanyLetter->created_at));
            if($Year === date('Y')){
                $theNumber = $LatestNumber;
            }
            else{
                $theNumber = 0;
            }
        }
        else
        {
            $theNumber = 0;
        }

        $numbers = sprintf("%04s", $theNumber+1);
        if($GetType->code_of_type === 'SKD_DIR'){
            $NewNumbers = $numbers."/".$data['company_code']."/".$GetType->code_of_type."/".$GetCategory->category_letter."/".$data['area_code']."/";
        }
        else
        {
            $NewNumbers = $numbers."/".$data['company_code']."/".$GetType->code_of_type."/".$GetCategory->category_letter."/".$data['area_code']."/".$data['department_code']."/";
        }

        $NewNumbers .= $this->Romawi(date('m'))."/".date('Y');
        $StartNumber = $NewNumbers;
        $LastNumber  = null;
        $DataInsert = [];
        if($count > 1){
            for($i=1; $i <= $count; $i++){
                $theNumber = $theNumber + 1;
                $number = sprintf("%04s", $theNumber);
                if($GetType->code_of_type === 'SKD_DIR'){
                    $NewNumber = $number."/".$data['company_code']."/".$GetType->code_of_type."/".$GetCategory->category_letter."/".$data['area_code']."/";
                }
                else
                {
                    $NewNumber = $number."/".$data['company_code']."/".$GetType->code_of_type."/".$GetCategory->category_letter."/".$data['area_code']."/".$data['department_code']."/";
                }        
                // $NewNumber = $number."/".$data['company_code']."/".$GetType->code_of_type."/".$GetCategory->category_letter."/".$data['area_code']."/".$data['department_code']."/";
                $NewNumber .= $this->Romawi(date('m'))."/".date('Y');    
                $DataInsert[] =[
                        'doc_number' => $doc_number,
                        'number_of_letter' => $NewNumber,
                        'company_code' => $data['company_code'],
                        'type_id' => $GetType->id,
                        'category_id' => $GetCategory->id,
                        'area_code' => $data['area_code'],
                        'department_code' => $data['department_code'],
                        'title' => $data['title'],
                        'upload_path_document' => $data['upload_path_document'],
                        'confidential' => $data['confidential'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),        
                    ];
                        
                $LastNumber = $NewNumber;   
            }
        }
        else{            
            $number = sprintf("%04s", $theNumber+1);
            // $NewNumber = $number."/".$data['company_code']."/".$GetType->code_of_type."/".$GetCategory->category_letter."/".$data['area_code']."/".$data['department_code']."/";
            if($GetType->code_of_type === 'SKD_DIR'){
                $NewNumber = $numbers."/".$data['company_code']."/".$GetType->code_of_type."/".$GetCategory->category_letter."/".$data['area_code']."/";
            }
            else
            {
                $NewNumber = $numbers."/".$data['company_code']."/".$GetType->code_of_type."/".$GetCategory->category_letter."/".$data['area_code']."/".$data['department_code']."/";
            }
            
            $NewNumber .= $this->Romawi(date('m'))."/".date('Y');
            $DataInsert[] =[
                'doc_number' => $doc_number,
                'number_of_letter' => $NewNumber,
                'company_code' => $data['company_code'],
                'type_id' => $GetType->id,
                'category_id' => $GetCategory->id,
                'area_code' => $data['area_code'],
                'department_code' => $data['department_code'],
                'title' => $data['title'],
                'upload_path_document' => $data['upload_path_document'],
                'confidential' => $data['confidential'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),        
            ];    
            $LastNumber = $NewNumber;
        }
        $insert = CompanyLetterModel::insert($DataInsert);
        if($insert){
            return [
                'status' => true,
                'letter_start' => $StartNumber,
                'letter_end' => $LastNumber,
                'doc_number'=> $doc_number,
                'data' => $DataInsert
            ];
        }
        else{
            return [
                'status' => false
            ];
        }       
    }

}