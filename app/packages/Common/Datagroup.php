<?php

namespace Package\Common;
use DB;
class Datagroup{

    protected $data = [];
    public function ShowStructureTable($table_name){
        $column = DB::getSchemaBuilder()->getColumnListing($table_name);
        array_pop($column);
        array_pop($column);
        array_shift($column);
        $data = [];
        foreach($column as $col){
            $data[$col] = "";
        }
        array_push($this->data,$data);
        return $this;
    }

    public function ShowColumn($table_name){
        $column = DB::getSchemaBuilder()->getColumnListing($table_name);
        array_pop($column);
        array_pop($column);
        array_shift($column);
        $this->data = $column;
        return $this;
    }

    public function asArray(){
        return $this->data;
    }

    public function ShowStructureAndType($table_name){
        $column = DB::select("describe $table_name");
        array_pop($column);
        array_pop($column);
        array_shift($column);
        $this->data = $column;
        return $this;
    }

    public function ShowListingTable($search,$userid){
        $alltable = DB::table('listtable_rules')
                    ->where(['userid' => $userid,'table' => '%'])
                    ->count();
        if($alltable > 0){
            if($search){
                $tables = DB::table('listtables')
                // ->where('table','LIKE','%'.$search.'%')
                ->where('table_name','LIKE','%'.$search.'%')
                ->paginate(5);
            }
            else{
                $tables = DB::table('listtables')->paginate(5);
            }
        }
        else{
            $getTableList = DB::table('listtable_rules')->where(['userid' => $userid])
                            ->select('table')
                            ->distinct()
                            ->get();
            $table = [];
            foreach($getTableList as $item){
                array_push($table,$item->table);
            }
            if($search){
                $tables = DB::table('listtables')
                ->whereIn('table',$table)
                // ->where('table','LIKE','%'.$search.'%')
                ->where('table_name','LIKE','%'.$search.'%')
                ->paginate(5);
            }
            else{
                $tables = DB::table('listtables')
                              ->whereIn('table',$table)
                              ->paginate(5);
            }
        }

        $this->data = $tables;
        return $this;
    }


}
