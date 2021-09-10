<?php

namespace Package\Backup;

use App\Models\Common\ApplicationModel as Model;

class Application
{
    public static function backup(){
        $model = Model::all();
        $dir = __DIR__.'/../../../database/backup/application.json';
        $myfile = fopen($dir, "w") or die("Unable to open file!");
        $txt = $model;
        fwrite($myfile, $txt);
        fclose($myfile);   
        if(file_exists($dir)){
            return "file application.json was created!";
        }
        return "file application.json failure to created!";
    }

}