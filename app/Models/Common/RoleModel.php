<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;
    protected $table="roles";
    protected $fillable=[
        'role_code',
        'role_description',
    ];
}
