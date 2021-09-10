<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoleModel extends Model
{
    use HasFactory;
    protected $table ="user_roles";
    protected $fillable= [
        'secretkey',
        'role_code',
    ];

}
