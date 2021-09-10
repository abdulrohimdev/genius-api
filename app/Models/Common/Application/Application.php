<?php

namespace App\Models\Common\Application;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $table = "applications";
    protected $fillable=[
        'app_code','app_name','app_description','app_route_frontend_web',
        'app_icon_class'
    ];

}
