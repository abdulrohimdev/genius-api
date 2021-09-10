<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Common\ApplicationModel as Model;
class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = file_get_contents(__DIR__.'/../backup/application.json');
        $data = json_decode($data);
        foreach($data as $item){
            Model::create([
                'app_code' => $item->app_code,
                'app_name' => $item->app_name,
                'app_description' => $item->app_description,
                'app_route_frontend_web' => $item->app_route_frontend_web,
                'app_route_frontend_mobile' => $item->app_route_frontend_mobile,
                'app_icon_class' => $item->app_icon_class,
                'app_icon_image' => $item->app_icon_image,
            ]);            
        }
    }
}
