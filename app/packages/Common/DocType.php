<?php

/* 
    Author by Abdul Rohim
    Library Code Message
*/

namespace Package\Common;

use App\Models\Common\Doctype as DoctypeModel;

class Doctype{

    public function GetDocNumber($doctype){
        date_default_timezone_set('Asia/Jakarta');
        $doctype = strtoupper($doctype);
        $GetData = DoctypeModel::where(['doctype' => $doctype])->orderBy('id','desc')->first();
        if($GetData){
            $DocNumber = $GetData->doc_number;
            $_array = explode("-",$DocNumber);
            $number = $_array[2];    
            $number = $number + 1;
            $doc_number = $doctype."-".date('dmY')."-".$number;            
        }
        else{
            $doc_number = $doctype."-".date('dmY')."-1";
        }
        return $doc_number;
    }

    public function CreateDocNumber($doctype,$create_by,$status){
        $doc_number = $this->GetDocNumber($doctype);
        $data = [
            'doc_number' => $doc_number,
            'doctype' => $doctype,
            'status' => $status,
            'create_by' => $create_by,
            'approval_note' => '',
        ];
        $create = DoctypeModel::create($data);
        if($create){
            return $doc_number;
        }
        else{
            return (false);
        }
    }
}