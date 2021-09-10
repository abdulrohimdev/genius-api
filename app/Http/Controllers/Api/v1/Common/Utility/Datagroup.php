<?php

namespace App\Http\Controllers\Api\v1\Common\Utility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Common\UserModel;
use App\Models\Common\UserRoleModel;

use Package\Common\Message;
use Package\Common\VerifyAccount;

use Package\Common\Datagroup as DatagroupPackage;
use Package\Utility\ExcelToArray;
use DB;

class Datagroup extends Controller
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

    public function GetColumnFromTable(Request $r){
        $user = $this->verify->first();
        if($user){
            $table = $r->input('table');
            try{
                $datagroup = new DatagroupPackage();
                $format = $datagroup->ShowStructureTable($table)->asArray();
                $structure = $datagroup->ShowStructureAndType($table)->asArray();

                $data = [];

                foreach($format[0] as $key => $value){
                    array_push($data,[
                        'key' => $key,
                        'selected' => true
                    ]);
                }

                return Response()->json([
                    'status' => true,
                    'format' => $format,
                    'data'   => $data,
                    'structure' => $structure
                ],200,[],JSON_PRETTY_PRINT);

            }
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                        'use' => true,
                        'lang' => 'en'])]);

    }

    public function GetListTable(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $datagroup = new DatagroupPackage();
                $search = $r->input('search');
                $table = $datagroup->ShowListingTable($search,$user->username)->asArray();
                return Response()->json([
                    'status' => true,
                    'data' => $table,
                ],200,[],JSON_PRETTY_PRINT);
            }
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                        'use' => true,
                        'lang' => 'en'])]);

    }

    public function Store(Request $r){
        $user = $this->verify->first();
        if($user){
            $table = $r->input('table');
            $datagroup = new DatagroupPackage();
            $column = $datagroup->ShowColumn($table)->asArray();
            $param = $r->all();
            $file = $r->file('master_data')->getPathName();
            $ExcelToArray = new ExcelToArray();
            $data = $ExcelToArray->file($param['master_data'],$file);
            if(count($column) === count($data[0])){
                $DataForInsert = [];
                for($i=1; $i < count($data); $i++){
                    $DataColumn = [];
                    for($j=0; $j < count($column); $j++){
                        $DataColumn[$column[$j]] = $data[$i][$j];
                    }
                    $DataColumn['created_at'] = \Carbon\Carbon::now();
                    $DataColumn['updated_at'] = \Carbon\Carbon::now();
                    array_push($DataForInsert,$DataColumn);
                }
                try{
                    $insert = DB::table($table)->insert($DataForInsert);
                    if($insert){
                        return Response()->json([
                            'status' => true,
                            'message' => $this->message->get(24,[
                                'use' => true,
                                'lang' => $user->language])
                            ],200,[],JSON_PRETTY_PRINT);
                    }
                    return Response()->json([
                        'status' => false,
                        'message' => $this->message->get(25,[
                            'use' => true,
                            'lang' => $user->language])
                        ],200,[],JSON_PRETTY_PRINT);

                }
                catch(\Exception $e){
                    return Response()->json([
                        'status' => false,
                        'message' => $e->getMessage()
                        ],200,[],JSON_PRETTY_PRINT);
                }
            }
            else{
                return Response()->json([
                    'status' => false,
                    'column' => $column,
                    'dataColumn' => $data[0],
                    'message' => 'Count Column not match'
                ],200,[],JSON_PRETTY_PRINT);
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
            $table = $r->input('table');
            $datagroup = new DatagroupPackage();
            $column = $datagroup->ShowColumn($table)->asArray();
            $param = $r->all();
            $file = $r->file('master_data')->getPathName();
            $ExcelToArray = new ExcelToArray();
            $data = $ExcelToArray->file($param['master_data'],$file);
            if(count($column) === count($data[0])){
                $DataForDelete = [];
                for($i=1; $i < count($data); $i++){
                    $DataColumn = [];
                    for($j=0; $j < count($column); $j++){
                        $DataColumn[$column[$j]] = $data[$i][$j];
                    }
                    array_push($DataForDelete,$DataColumn);
                }
                try{
                    $countBeforeDelete = count($DataForDelete);
                    $countAfterDelete = 0;
                    $dataNotDelete = [];
                    foreach($DataForDelete as $item){
                        $delete = DB::table($table)->where($item);
                        if($delete->delete()){
                            $countAfterDelete++;
                        }
                        else{
                            array_push($dataNotDelete,$item);
                        }
                    }
                    if($countBeforeDelete === $countAfterDelete){
                        return Response()->json([
                            'status' => true,
                            'message' => $this->message->get(18,[
                                'use' => true,
                                'lang' => $user->language])
                            ],200,[],JSON_PRETTY_PRINT);
                    }
                    return Response()->json([
                        'status' => false,
                        'dataCanotDelete' => $dataNotDelete,
                        'message' => $this->message->get(19,[
                            'use' => true,
                            'lang' => $user->language])
                        ],200,[],JSON_PRETTY_PRINT);

                }
                catch(\Exception $e){
                    return Response()->json([
                        'status' => false,
                        'data'  => $DataForDelete,
                        'message' => $e->getMessage()
                        ],200,[],JSON_PRETTY_PRINT);
                }
            }
            else{
                return Response()->json([
                    'status' => false,
                    'message' => 'Count Column not match'
                ],200,[],JSON_PRETTY_PRINT);
            }
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                        'use' => true,
                        'lang' => 'en'])]);
    }

    public function user_table(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $userid = $r->input('userid');
                $table = $r->input('table');
                $data = [];
                foreach($table as $item){
                    array_push($data,[
                        'userid' => $userid,
                        'table' => $item['value'],
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now()
                    ]);
                }

                $create = DB::table('listtable_rules')->insert($data);
                if($create){
                    return Response()->json([
                        'status' => true,
                        'message' => $this->message->get(16,[
                                    'use' => true,
                                    'lang' => 'en'])]);
                }
                return Response()->json([
                    'status' => false,
                    'message' => $this->message->get(17,[
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

    public function ExportDataFromTable(Request $r){
        $user = $this->verify->first();
        if($user){
            try{
                $table = $r->input('table');
                $column = $r->input('column');
                $dataColumn = [];
                foreach($column as $key){
                    if($key['selected'] === true){
                        array_push($dataColumn,"`".$key['key']."`");
                    }
                }
                $dataColumn = implode(",",$dataColumn);
                $data = DB::table($table)->select(DB::raw($dataColumn))->get();
                if($data){
                    return Response()->json([
                        'status' => true,
                        'data'   => $data,
                    ],200,[],JSON_PRETTY_PRINT);
                }
            }
            catch(\Exception $e){
                return Response()->json([
                    'status' => false,
                    'message' => $e->getMessage()]);
            }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                        'use' => true,
                        'lang' => 'en'])]);
        }
    }
}
