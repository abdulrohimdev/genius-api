<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = file_get_contents(__DIR__.'/../backup/user_role.json');
        $data = json_decode($data);
        foreach($data as $item){
            \App\Models\Common\UserRoleModel::create([
                'secretkey' => $item->secretkey,
                'role_code' => $item->role_code,
            ]);            
        }

    }
}
