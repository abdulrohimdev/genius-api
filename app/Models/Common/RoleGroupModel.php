<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleGroupModel extends Model
{
    use HasFactory;
    protected $table ="role_groups";
    protected $fillable= [
        'role_code_id',
        'application_code',
    ];

}
