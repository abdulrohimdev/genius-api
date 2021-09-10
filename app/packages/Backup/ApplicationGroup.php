<?php

namespace Package\Backup;

use App\Models\Common\ApplicationGroup as Model;

class ApplicationGroup
{
    public static function backup(){
        $model = Model::all();
        $dir = __DIR__.'/../../../database/backup/application_groups.json';
        $myfile = fopen($dir, "w") or die("Unable to open file!");
        $txt = $model;
        fwrite($myfile, $txt);
        fclose($myfile);   
        if(file_exists($dir)){
            return "file application_groups.json was created!";
        }
        return "file application_groups.json failure to created!";
    }

}