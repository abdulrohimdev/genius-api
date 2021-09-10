<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Common\ApplicationGroup as Model;

class ApplicationGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = file_get_contents(__DIR__.'/../backup/application_groups.json');
        $data = json_decode($data);
        foreach($data as $item){
            Model::create([
                'id' => $item->id,
                'app_group_name' => $item->app_group_name,
                'app_group_parent' => $item->app_group_parent,
                'app_group_type' => $item->app_group_type,
                'application_code' => $item->application_code,
                'description' => $item->description,
            ]);            

        }
    }
}
