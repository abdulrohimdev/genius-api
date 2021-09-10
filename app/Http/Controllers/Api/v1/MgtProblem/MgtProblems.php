<?php

namespace App\Http\Controllers\Api\v1\MgtProblem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Common\UserModel;
use App\Models\Common\UserRoleModel;
use Illuminate\Pagination\Paginator;

use Package\Common\Message;
use Package\Common\VerifyAccount;
use Carbon\Carbon;
use DB;

use App\Models\MgtProblems\MasterProblem;
use App\Models\MgtProblems\FormProblem;
use App\Models\MgtProblems\FormCaseProblem;

class MgtProblems extends Controller
{

    public $verify;
    public $message;
    public function __construct(Request $r){
        date_default_timezone_set('Asia/Jakarta');
        $this->message = new Message();
        $this->verify = new VerifyAccount(
            $r->header('apikey'),
            $r->header('secretkey')
        );
    }

    public function Store(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $area     = "Quality";
            $company  = $user->company_code;
            $location = $r->input('location');
            $process = $r->input('process');
            $type     = $r->input('type');
            $product  = $r->input('product');
            $line       = $r->input('line') ?? 'No Line';
            $createby = $user->username;
            try{
                $create   = FormProblem::create([
                    'company_code' => $company,
                    'management_area' => $area,
                    'location' => $location,
                    'process' => $process,
                    'type' => $type,
                    'product' => $product,
                    'line' => $line,
                    'create_by' => $createby
                ]);
                $case = $r->input('case');
                $data = [];
                foreach($case as $item){
                    array_push($data,[
                        'problem_id'=> $create->id,
                        'case_type' => $item['case_type'],
                        'quantity' => $item['quantity'],
                        'case' => $item['problem'],
                        'decision' => $item['decision'],
                        'note' => $item['note'],
                        'image' => $item['image'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
                $insertMany = FormCaseProblem::insert($data);
                if($insertMany){
                    return Response()->json([
                        'status' => true,
                        'data' => $data,
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

    public function GetProblems(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            if($r->input('search')){
                $data = FormProblem::where(['create_by' => $user->username])
                        ->whereDate('created_at',date('Y-m-d',strtotime($r->input('date'))))
                        ->where('location','LIKE','%'.$r->input('search').'%')
                        // ->orWhere('type','LIKE','%'.$r->input('search').'%')
                        // ->orWhere('product','LIKE','%'.$r->input('search').'%')
                        ->orderBy('id','desc')->get();
            }
            else{
                $data = FormProblem::where(['create_by' => $user->username])
                        ->whereDate('created_at',date('Y-m-d',strtotime($r->input('date'))))
                        ->orderBy('id','desc')->get();
            }
            $result = [];
            foreach($data as $item){
                array_push($result,[
                    'id' => $item->id,
                    'company_code' => $item->company_code,
                    'management_area' => $item->management_area,
                    'location' => $item->location,
                    'process' => $item->process,
                    'type' => $item->type,
                    'product' => $item->product,
                    'created_at' => date("d-m-Y h:i:s",strtotime($item->created_at)),
                    'updated_at' => $item->updated_at,
                ]);
            }
            return Response()->json([
                'status' => true,
                'data' => $result
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);

    }

    public function destroy(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $problem_id = $r->input('id');
            try{
                $delete_first = FormProblem::where(['id' => $problem_id]);
                if($delete_first->delete()){
                    $delete_second = FormCaseProblem::where(['problem_id' => $problem_id]);
                    if($delete_second->count() > 0 && $delete_second->delete()){
                        return Response()->json([
                            'status' => true,
                            'message' => $this->message->get(18,[
                                'use' => true,
                                'lang' => 'en'])]);
                    }
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


    public function data(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $problem_id = $r->input('id');
            $problem_parent = FormProblem::where(['id' => $problem_id])->first();
            $problem_child  = FormCaseProblem::where(['problem_id' => $problem_id])->get();

            return Response()->json([
                'status' => true,
                'created_at' => date('d-m-Y H:i:s',strtotime($problem_parent->created_at)),
                'parent' => $problem_parent,
                'child'  => $problem_child
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);

    }

    public function delete_case(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $id = $r->input('id');
            $case  = FormCaseProblem::where(['id' => $id]);
            if($case->delete()){
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
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);

    }

    public function store_case(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $data =  $r->input('case');
            $case  = FormCaseProblem::create($data);
            if($case){
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
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);

    }

    public function getChart(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $by_param = $r->input('param');
            $chart_type = $r->input('chart_type');
            $where = $r->input('where');
            $date = date('Y-m-d',strtotime($r->input('created')));
            $fromDate = date('Y-m-d',strtotime($r->input('fromDate')));
            $toDate = date('Y-m-d',strtotime($r->input('toDate')));
            $dataOnWhere = [];
            if(count($where) > 0){
                foreach($where as $val){
                    if($val['name'] === 'case'){
                        $dataOnWhere['case_type'] = $val['value'];
                    }
                    else if($val['name'] === 'problem'){
                        $dataOnWhere['case'] = $val['value'];
                    }
                    else{
                        $dataOnWhere[$val['name']] = $val['value'];
                    }
                }
            }
            if($chart_type === 'pie'){
                $company = $user->company_code;
                $area = "Quality";
                if($by_param === 'case' || $by_param === 'problem'){
                    if($by_param === 'case'){
                        $by_param = 'case_type';
                    }
                    else if($by_param === 'problem'){
                        $by_param = 'case';
                    }
                    if(count($dataOnWhere) > 0){
                        $wheresData = $dataOnWhere;
                        unset($wheresData['case_type']);
                        unset($wheresData['case']);
                        $get = DB::table('management_form_problems')
                                ->select('id')
                                ->where($wheresData)
                                ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                ->get();
                        $problem_id = [];
                        foreach($get as $val){
                            array_push($problem_id,(int) $val->id);
                        }

                        foreach($wheresData as $key => $val){
                            unset($dataOnWhere[$key]);
                        }
                        $data = DB::table('form_case_problems')
                                ->select(DB::raw('`'.$by_param.'` as name, COUNT(*) as value'))
                                ->where($dataOnWhere)
                                ->whereIn('problem_id',$problem_id)
                                ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                ->groupBy($by_param)
                                ->get();
                        $line = "310";
                    }
                    else{
                        $data = DB::table('form_case_problems')
                        ->select(DB::raw('`'.$by_param.'` as name, COUNT(*) as value'))
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "319";
                    }
                }
                else{
                    if(isset($dataOnWhere['case_type']) || isset($dataOnWhere['case'])){
                        $isWhere = [];
                        foreach($dataOnWhere as $key => $val){
                            if($key === 'case_type' || $key === 'case'){
                                $isWhere[$key] = $val;
                            }
                        }
                        $data_id = DB::table('form_case_problems')
                                       ->select('problem_id')
                                       ->where($isWhere)
                                       ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                       ->get();
                        $problem_id = [];
                        foreach($data_id as $val){
                            array_push($problem_id,(int) $val->problem_id);
                        }

                        $condition=[];
                        unset($dataOnWhere['case_type']);
                        unset($dataOnWhere['case']);
                        foreach($dataOnWhere as $key => $val){
                            array_push($condition,"s.$key='$val'");
                        }
                        $condition = implode(" and ",$condition);
                        if($condition){
                            $condition .= " and ";
                        }

                        $case_condition = [];

                        foreach($isWhere as $key => $val){
                            array_push($case_condition,"f.`$key`='$val'");
                        }
                        $case_condition = implode(" and ",$case_condition);
                        if($case_condition){
                            $case_condition .= " and ";
                        }
                        $data = DB::table('management_form_problems')
                        ->select($by_param.' as name',
                            DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE $case_condition f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.$by_param=management_form_problems.$by_param and  (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate'))) AS value")
                        )
                        ->where(['company_code' => $company,'management_area' => $area])
                        ->whereIn('id',$problem_id)
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "343";
                    }
                    else{
                        $condition=[];
                        foreach($dataOnWhere as $key => $val){
                            array_push($condition,"s.$key='$val'");
                        }
                        $condition = implode(" and ",$condition);
                        if($condition){
                            $condition .= " and ";
                        }

                        $data = DB::table('management_form_problems')
                                    ->select(
                                        "$by_param as name",
                                        DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.$by_param=management_form_problems.$by_param and  (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate'))) AS value")
                                     )
                        ->where(['company_code' => $company,'management_area' => $area])
                        ->where($dataOnWhere)
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "352";
                    }
                }
                return Response()->json([
                    'status' => true,
                    'data'   => $data,
                    'where'  => $dataOnWhere,
                    'line'  => $line
                ]);
            }
            else{
                $company = $user->company_code;
                $area = "Quality";
                if($by_param === 'case' || $by_param === 'problem'){
                    if($by_param === 'case'){
                        $by_param = 'case_type';
                    }
                    else if($by_param === 'problem'){
                        $by_param = 'case';
                    }
                    if(count($dataOnWhere) > 0){
                        $wheresData = $dataOnWhere;
                        unset($wheresData['case_type']);
                        unset($wheresData['case']);
                        $get = DB::table('management_form_problems')
                                ->select('id')
                                ->where($wheresData)
                                ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                ->get();
                        $problem_id = [];
                        foreach($get as $val){
                            array_push($problem_id,(int) $val->id);
                        }
                        foreach($wheresData as $key => $val){
                            unset($dataOnWhere[$key]);
                        }
                        $data = DB::table('form_case_problems')
                                ->select(DB::raw('`'.$by_param.'` as name, COUNT(*) as value'))
                                ->where($dataOnWhere)
                                ->whereIn('problem_id',$problem_id)
                                ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                ->groupBy($by_param)
                                ->get();
                        $line = "394";
                    }
                    else{
                        $data = DB::table('form_case_problems')
                        ->select(DB::raw('`'.$by_param.'` as name, COUNT(*) as value'))
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "403";
                    }
                }
                else{
                    if(isset($dataOnWhere['case_type']) || isset($dataOnWhere['case'])){
                        $isWhere = [];
                        foreach($dataOnWhere as $key => $val){
                            if($key === 'case_type' || $key === 'case'){
                                $isWhere[$key] = $val;
                            }
                        }
                        $data_id = DB::table('form_case_problems')
                                       ->select('problem_id')
                                       ->where($isWhere)
                                       ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                       ->get();
                        $problem_id = [];
                        foreach($data_id as $val){
                            array_push($problem_id,(int) $val->problem_id);
                        }
                        $condition=[];
                        unset($dataOnWhere['case_type']);
                        unset($dataOnWhere['case']);
                        foreach($dataOnWhere as $key => $val){
                            array_push($condition,"s.$key='$val'");
                        }

                        $condition = implode(" and ",$condition);
                        if($condition){
                            $condition .= " and ";
                        }

                        $case_condition = [];

                        foreach($isWhere as $key => $val){
                            array_push($case_condition,"f.`$key`='$val'");
                        }
                        $case_condition = implode(" and ",$case_condition);
                        if($case_condition){
                            $case_condition .= " and ";
                        }
                        $data = DB::table('management_form_problems')
                        ->select($by_param.' as name',
                            DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE $case_condition f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.$by_param=management_form_problems.$by_param and  (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate'))) AS value")
                        )
                        ->where(['company_code' => $company,'management_area' => $area])
                        ->whereIn('id',$problem_id)
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "428";
                    }
                    else{
                        $condition=[];
                        foreach($dataOnWhere as $key => $val){
                            array_push($condition,"s.$key='$val'");
                        }

                        $condition = implode(" and ",$condition);
                        if($condition){
                            $condition .= " and ";
                        }

                        $data = DB::table('management_form_problems')
                                    ->select(
                                        "$by_param as name",
                                        DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.$by_param=management_form_problems.$by_param and  (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate'))) AS value")
                                     )
                        ->where(['company_code' => $company,'management_area' => $area])
                        ->where($dataOnWhere)
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "437";
                    }
                }

                $xAxis = [];
                $series = [];
                foreach($data as $item){
                    array_push($xAxis,$item->name);
                    array_push($series,$item->value);
                }
                return Response()->json([
                    'status' => true,
                    'xAxis' => $xAxis,
                    'series' => $series,
                    'where'  => $dataOnWhere,
                    'line'  => $line
                ]);
            }
         }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function chartReload(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            $data = $r->input('data');
            $result = [];
            $index = 0;
            date_default_timezone_set('Asia/Jakarta');
            $fromDate = "";
            $toDate = "";
            foreach($data as $val){
                if($index === 0){
                    $where = [];
                }
                else
                {
                    $where = (array) $val['where'];
                    array_splice($where,$index);
                }
                $fromDate = date('Y-m-d',strtotime($val['fromDate']));
                $toDate = date('Y-m-d',strtotime($val['toDate']));
                array_push($result,[
                    "typeChart" => $val['chart_type'],
                    "data" => $this->getData($val['param'],$val['chart_type'],$where,$fromDate,$toDate),
                    "name"  => $val['param']
                    ]
                 );
                $index++;
            }
            return Response()->json([
                'status' => true,
                'data'   => $result,
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function getData($by_param,$chart_type,$where,$fromDate,$toDate){
            $user = $this->verify->first();
            $dataOnWhere = [];
            if(count($where) > 0){
                foreach($where as $val){
                    if($val['name'] === 'case'){
                        $dataOnWhere['case_type'] = $val['value'];
                    }
                    else if($val['name'] === 'problem'){
                        $dataOnWhere['case'] = $val['value'];
                    }
                    else{
                        $dataOnWhere[$val['name']] = $val['value'];
                    }
                }
            }
            if($chart_type === 'pie'){
                $company = $user->company_code;
                $area = "Quality";
                if($by_param === 'case' || $by_param === 'problem'){
                    if($by_param === 'case'){
                        $by_param = 'case_type';
                    }
                    else if($by_param === 'problem'){
                        $by_param = 'case';
                    }
                    if(count($dataOnWhere) > 0){
                        $wheresData = $dataOnWhere;
                        unset($wheresData['case_type']);
                        unset($wheresData['case']);
                        $get = DB::table('management_form_problems')
                                ->select('id')
                                ->where($wheresData)
                                ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                ->get();
                        $problem_id = [];
                        foreach($get as $val){
                            array_push($problem_id,(int) $val->id);
                        }

                        foreach($wheresData as $key => $val){
                            unset($dataOnWhere[$key]);
                        }
                        $data = DB::table('form_case_problems')
                                ->select(DB::raw('`'.$by_param.'` as name, COUNT(*) as value'))
                                ->where($dataOnWhere)
                                ->whereIn('problem_id',$problem_id)
                                ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                ->groupBy($by_param)
                                ->get();
                        $line = "310";
                    }
                    else{
                        $data = DB::table('form_case_problems')
                        ->select(DB::raw('`'.$by_param.'` as name, COUNT(*) as value'))
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "319";
                    }
                }
                else{
                    if(isset($dataOnWhere['case_type']) || isset($dataOnWhere['case'])){
                        $isWhere = [];
                        foreach($dataOnWhere as $key => $val){
                            if($key === 'case_type' || $key === 'case'){
                                $isWhere[$key] = $val;
                            }
                        }
                        $data_id = DB::table('form_case_problems')
                                       ->select('problem_id')
                                       ->where($isWhere)
                                       ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                       ->get();
                        $problem_id = [];
                        foreach($data_id as $val){
                            array_push($problem_id,(int) $val->problem_id);
                        }

                        $condition=[];
                        unset($dataOnWhere['case_type']);
                        unset($dataOnWhere['case']);
                        foreach($dataOnWhere as $key => $val){
                            array_push($condition,"s.$key='$val'");
                        }
                        $condition = implode(" and ",$condition);
                        if($condition){
                            $condition .= " and ";
                        }

                        $case_condition = [];

                        foreach($isWhere as $key => $val){
                            array_push($case_condition,"f.`$key`='$val'");
                        }
                        $case_condition = implode(" and ",$case_condition);
                        if($case_condition){
                            $case_condition .= " and ";
                        }
                        $data = DB::table('management_form_problems')
                        ->select($by_param.' as name',
                            DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE $case_condition f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.$by_param=management_form_problems.$by_param and (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate'))) AS value")
                        )
                        ->where(['company_code' => $company,'management_area' => $area])
                        ->whereIn('id',$problem_id)
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "343";
                    }
                    else{
                        $condition=[];
                        foreach($dataOnWhere as $key => $val){
                            array_push($condition,"s.$key='$val'");
                        }
                        $condition = implode(" and ",$condition);
                        if($condition){
                            $condition .= " and ";
                        }

                        $data = DB::table('management_form_problems')
                                    ->select(
                                        "$by_param as name",
                                        DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.$by_param=management_form_problems.$by_param and (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate'))) AS value")
                                     )
                        ->where(['company_code' => $company,'management_area' => $area])
                        ->where($dataOnWhere)
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "352";
                    }
                }
                return $data;
            }
            else{
                $company = $user->company_code;
                $area = "Quality";
                if($by_param === 'case' || $by_param === 'problem'){
                    if($by_param === 'case'){
                        $by_param = 'case_type';
                    }
                    else if($by_param === 'problem'){
                        $by_param = 'case';
                    }
                    if(count($dataOnWhere) > 0){
                        $wheresData = $dataOnWhere;
                        unset($wheresData['case_type']);
                        unset($wheresData['case']);
                        $get = DB::table('management_form_problems')
                                ->select('id')
                                ->where($wheresData)
                                ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                ->get();
                        $problem_id = [];
                        foreach($get as $val){
                            array_push($problem_id,(int) $val->id);
                        }
                        foreach($wheresData as $key => $val){
                            unset($dataOnWhere[$key]);
                        }
                        $data = DB::table('form_case_problems')
                                ->select(DB::raw('`'.$by_param.'` as name, COUNT(*) as value'))
                                ->where($dataOnWhere)
                                ->whereIn('problem_id',$problem_id)
                                ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                ->groupBy($by_param)
                                ->get();
                        $line = "394";
                    }
                    else{
                        $data = DB::table('form_case_problems')
                        ->select(DB::raw('`'.$by_param.'` as name, COUNT(*) as value'))
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "403";
                    }
                }
                else{
                    if(isset($dataOnWhere['case_type']) || isset($dataOnWhere['case'])){
                        $isWhere = [];
                        foreach($dataOnWhere as $key => $val){
                            if($key === 'case_type' || $key === 'case'){
                                $isWhere[$key] = $val;
                            }
                        }
                        $data_id = DB::table('form_case_problems')
                                       ->select('problem_id')
                                       ->where($isWhere)
                                       ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                                       ->get();
                        $problem_id = [];
                        foreach($data_id as $val){
                            array_push($problem_id,(int) $val->problem_id);
                        }
                        $condition=[];
                        unset($dataOnWhere['case_type']);
                        unset($dataOnWhere['case']);
                        foreach($dataOnWhere as $key => $val){
                            array_push($condition,"s.$key='$val'");
                        }

                        $condition = implode(" and ",$condition);
                        if($condition){
                            $condition .= " and ";
                        }

                        $case_condition = [];

                        foreach($isWhere as $key => $val){
                            array_push($case_condition,"f.`$key`='$val'");
                        }
                        $case_condition = implode(" and ",$case_condition);
                        if($case_condition){
                            $case_condition .= " and ";
                        }
                        $data = DB::table('management_form_problems')
                        ->select($by_param.' as name',
                            DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE $case_condition f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.$by_param=management_form_problems.$by_param and (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate'))) AS value")
                        )
                        ->where(['company_code' => $company,'management_area' => $area])
                        ->whereIn('id',$problem_id)
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "428";
                    }
                    else{
                        $condition=[];
                        foreach($dataOnWhere as $key => $val){
                            array_push($condition,"s.$key='$val'");
                        }

                        $condition = implode(" and ",$condition);
                        if($condition){
                            $condition .= " and ";
                        }

                        $data = DB::table('management_form_problems')
                                    ->select(
                                        "$by_param as name",
                                        DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.$by_param=management_form_problems.$by_param and (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate'))) AS value")
                                     )
                        ->where(['company_code' => $company,'management_area' => $area])
                        ->where($dataOnWhere)
                        ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                        ->groupBy($by_param)
                        ->get();
                        $line = "437";
                    }
                }

                $xAxis = [];
                $series = [];
                foreach($data as $item){
                    array_push($xAxis,$item->name);
                    array_push($series,$item->value);
                }
                return [
                    'xAxis' => $xAxis,
                    'series' => $series,
                    'line' => $line
                ];
            }
    }

    public function detail(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            date_default_timezone_set('Asia/Jakarta');
            $arraySelected = $r->input('arraySelected');
            $fromWhere = $r->input('fromWhere');
            $fromIndex = $r->input('fromIndex');
            $search = $r->input('search');
            $fromDate = date('Y-m-d',strtotime($r->input('fromDate')));
            $toDate = date('Y-m-d',strtotime($r->input('toDate')));
            $data = [];
            $index = 0;
            foreach($fromWhere as $item){
                if($index <= (int) $fromIndex){
                    array_push($data,$item);
                }
                $index++;
            }
            $data[(int) $fromIndex] = $arraySelected;
            $forWhere = [
                'company_code' => 'DP',
                'management_area' => 'Quality',
            ];
            $dataCondition = [];
            $dataFormCase = [];
            $whereChild = [];
            foreach($data as $item){
                $whereResponse[$item['name']] = $item['value'];
                if($item['name'] !== 'case' && $item['name'] !== 'problem'){
                    array_push($dataCondition,"s.$item[name]='$item[value]'");
                    $forWhere[$item['name']] = $item['value'];
                }
                else{
                    if($item['name'] === 'case'){
                        array_push($dataFormCase,"f.case_type='$item[value]'");
                        $whereChild['case_type'] = $item['value'];
                    }
                    else if($item['name'] === 'problem'){
                        array_push($dataFormCase,"f.case='$item[value]'");
                        $whereChild['case'] = $item['value'];
                    }
                    else{
                        array_push($dataFormCase,"f.$item[name]='$item[value]'");
                        $whereChild[$item['name']] = $item['value'];
                    }
                }
            }

            $condition = implode(" and ",$dataCondition);
            if($condition){
                $condition .= " and ";
            }

            $formCaseCondition = implode(" and ",$dataFormCase);
            if($formCaseCondition){
                $formCaseCondition .= " and ";
            }

            $param = $arraySelected['name'];

            $table = DB::table('management_form_problems')
                    ->select('create_by as employee_id',
                        DB::raw('(select users.fullname from users where users.employee_id=management_form_problems.create_by) as fullname'),
                        DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE $formCaseCondition f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.create_by=management_form_problems.create_by and (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate'))) AS value")
                    )
                    ->where('create_by','LIKE','%'.$search.'%')
                    ->where($forWhere)
                    ->where(DB::raw("( SELECT COUNT(*) FROM form_case_problems f WHERE $formCaseCondition f.problem_id IN ((SELECT id FROM management_form_problems s WHERE $condition s.create_by=management_form_problems.create_by and (DATE_FORMAT(s.created_at,'%Y-%m-%d')) between '$fromDate' and '$toDate')))"),'>',0)
                    ->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),[$fromDate,$toDate])
                    ->groupBy('employee_id')
                    ->paginate(10);

            return Response()->json([
                'status' => true,
                'data'  => $table,
                'whereParent' => $forWhere,
                'whereChild'  => $whereChild,
                'fromDate' => [$fromDate,$toDate],
                'childCondition' => $formCaseCondition,
                'parentCondition' => $condition
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }


    public function productDetail(Request $r){
        $user = $this->verify->first();
        if($this->verify->count() > 0){
            date_default_timezone_set('Asia/Jakarta');
            $data = $r->input('data');
            $create_by = $data['create_by'];
            $whereParent = $this->arrayToQueryString("m",$data['whereParent']);
            $whereChild  = $this->arrayToQueryString("f",$data['whereChild']);
            $dateBetween = $data['dateBetween'];
            // $dateBetween = "'$dateBetween[0]' and '$dateBetween[1]'";
            if($whereParent){
                $whereParent .= " and ";
            }

            if($whereChild){
                $child = "and ";
                $child .= $whereChild;
                $whereChild = $child;
            }

            $dataChild = [];
            foreach($data['whereChild'] as $key => $val){
                $dataChild['form_case_problems.'.$key] = $val;
            }

            $table = DB::table('management_form_problems')
                         ->join('form_case_problems',function($join) use ($data) {
                            $join->on('form_case_problems.problem_id','=','management_form_problems.id');
                         })
                         ->where($data['whereParent'])
                         ->where($dataChild)
                         ->where(['create_by' => $create_by])
                         ->whereBetween(DB::raw("(DATE_FORMAT(management_form_problems.created_at,'%Y-%m-%d'))"),$dateBetween)
                         ->paginate(4);

            // $sql = "SELECT m.location,
            //     m.`process`,
            //     m.`type`,
            //     m.product,
            //     m.line,
            //     f.case_type,
            //     f.`case`,
            //     f.decision,
            //     f.note,
            //     f.quantity,
            //     f.image
            // FROM management_form_problems m
            // INNER JOIN form_case_problems f ON f.problem_id=m.id
            //     $whereChild
            // WHERE
            //     $whereParent
            //     m.create_by='$create_by' AND
            //     DATE(m.created_at) BETWEEN $dateBetween
            // ";
            // $table = DB::select(DB::raw($sql));

            return Response()->json([
                'status' => true,
                // 'data'  => new Paginator($table,4),
                'data'  => $table,
            ]);
        }
        return Response()->json([
            'status' => false,
            'message' => $this->message->get(3,[
                'use' => true,
                'lang' => 'en'])]);
    }

    public function arrayToQueryString($prefix,$array){
        if(is_array($array)){
            $data = [];
            foreach($array as $key => $val){
                array_push($data,$prefix.".`".$key."`='".$val."'");
            }
            $data = implode(" and ", $data);
            return $data;
        }
        return null;
    }


}
