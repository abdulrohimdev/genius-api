<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = file_get_contents(__DIR__.'/../backup/role.json');
        $data = json_decode($data);
        foreach($data as $item){
            \App\Models\Common\RoleModel::create([
                'role_code' => $item->role_code,
                'role_description' => $item->role_description,
            ]);            
        }
    }
}
