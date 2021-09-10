<?php
namespace Package\Common;
use BlueM\Tree;
class MyTree{
    private $result;
    private $tree;
    private $node;
    
    public function __construct($data=[]){
        $this->result = $data;
        $this->tree = new Tree($this->result);
    }

    // public function convert($data)
    // {
    //     $temp = [];
    //     foreach($data as $val){
    //         array_push($temp,[
    //             'id' => (int) $val->id,
    //             'parent' => (int) $val->app_group_parent,
    //             'title'  => $val->app_group_name,
    //             'app_code' => $val->application_code
    //         ]);
    //     }
    //     $this->result = $temp;
    //     return $this;
    // }

    public function getId($data){
        $temp = [];
        foreach($data as $val){
            array_push($temp,$val->id);
        }
        return $temp;
    }

    public function AppCode($data){
        $temp = [];
        foreach($data as $val){
            array_push($temp,$val->app_code);
        }
        return $temp;
    }

    public function getSelf($id){
        $this->node = $this->tree->getNodeById($id);
        return $this;
    }

    public function getParent(){
       $this->node =  $this->node->getParent();
       return $this;
    }

    public function GetAncestorsAndSelf(){
       $this->node =  $this->node->getAncestorsAndSelf();
       return $this;
    }

    public function hasChildren(){
        return $this->node->hasChildren();
        // return $this;
    }

    public function getDescendants(){
        $this->node = $this->node->getDescendants();
        return $this;
    }

    public function getDescendantsAndSelf(){
        $this->node = $this->node->getDescendantsAndSelf();
        return $this;
    }

    public function getChildren(){
        $this->node = $this->node->getChildren();
        return $this;
    }

    public function see($format=false){
        if($format)
        {
            return $this->FormatTree($this->node);
        }
        else
        {
            return $this->node;
        }
    }

    public function FormatTree($node){
        $temp = [];
        if(count((array) $node) > 0){
            foreach($node as $val){
                array_push($temp, [
                    'id' => $val->id,
                    'text' => $val->title,
                    'children' => $this->getSelf($val->id)->hasChildren(),
                    'app_code' => $val->app_code,
                    'icon' => $val->app_code ? 'assets/images/doc.png' : 'assets/images/dir.png'
                ]);                    
            }
        }
        if(count((array) $temp) < 1){
            array_push($temp, [
                'id' => $node->id,
                'text' => $node->title,
                'children' => $this->getSelf($node->id)->hasChildren(),
                'app_code' => $node->app_code,
                'icon' => $node->app_code ? 'assets/images/doc.png' : 'assets/images/dir.png'
            ]);
        }
       return $temp;
    }   

    public function rebuildData($data = []){
        $this->tree = $this->tree->rebuildWithData($data);
        return $this;
    }
}