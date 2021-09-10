<?php

namespace Package\Backup;

use App\Models\Common\UserRoleModel;

class UserRole
{
    public static function backup(){
        $model = UserRoleModel::all();
        $file = "user_role";
        $dir = __DIR__.'/../../../database/backup/'.$file.'.json';
        $myfile = fopen($dir, "w") or die("Unable to open file!");
        $txt = $model;
        fwrite($myfile, $txt);
        fclose($myfile);   
        if(file_exists($dir)){
            return "file $file.json was created!";
        }
        return "file $file.json failure to created!";
    }

}