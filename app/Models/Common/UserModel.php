<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $table ="users";

    protected $fillable= [
        'api_key',
        'secret_key',
        'device_id',
        'device_name',
        'username',
        'password',
        'fullname',
        'email',
        'phone',
        'locked',
        'company_code',
        'employee_id',
        'language',
        'operational',
        'photo',
    ];

}
