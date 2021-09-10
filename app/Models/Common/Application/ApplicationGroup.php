<?php

namespace App\Models\Common\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationGroup extends Model
{
    use HasFactory;
    protected $table = "application_groups";
    protected $fillable=[
        'app_group_name','app_group_parent','app_group_type','application_code',
        'description'
    ];

}
