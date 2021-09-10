<?php
namespace Package\Utility;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelToArray{
    public function file($data,$pathname){
        $ext = $data->getClientOriginalExtension();
        if($ext === 'csv'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        }else
        if($ext === 'xlsx'){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }else{
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }
        $spreadsheet = $reader->load($pathname);
        $allDataInSheet = $spreadsheet->getActiveSheet()->toArray();
        return $allDataInSheet;
    }
}
