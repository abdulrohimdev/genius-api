<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = file_get_contents(__DIR__.'/../backup/role_group.json');
        $data = json_decode($data);
        foreach($data as $item){
            \App\Models\Common\RoleGroupModel::create([
                'role_code_id' => $item->role_code_id,
                'application_code' => $item->application_code,
            ]);            
        }

    }
}
