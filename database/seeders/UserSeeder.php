<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = file_get_contents(__DIR__.'/../backup/user.json');
        $data = json_decode($data);
        foreach($data as $item){
            \App\Models\Common\UserModel::create([
                'api_key' => $item->api_key,//Str::random(16)
                'secret_key' => $item->secret_key,//Str::random(50)
                'device_id' => $item->device_id,
                'device_name' => $item->device_name,
                'username' => $item->username,
                'password' => $item->password,
                'fullname' => $item->fullname,
                'operational' => $item->operational,
                'email' => $item->email,
                'phone' => $item->phone,
                'locked' => $item->locked,
                'company_code' => $item->company_code,
                'employee_id' => $item->employee_id,
                'language' => $item->language,
            ]);            
        }

    }
}
