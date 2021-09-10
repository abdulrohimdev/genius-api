<?php

namespace Package\Backup;

use App\Models\Common\UserModel as Model;

class User
{
    public static function backup(){
        $model = Model::all();
        $dir = __DIR__.'/../../../database/backup/user.json';
        $myfile = fopen($dir, "w") or die("Unable to open file!");
        $txt = $model;
        fwrite($myfile, $txt);
        fclose($myfile);   
        if(file_exists($dir)){
            return "file user.json was created!";
        }
        return "file user.json failure to created!";
    }

}