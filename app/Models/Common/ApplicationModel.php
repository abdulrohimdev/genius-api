<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationModel extends Model
{
    use HasFactory;
    protected $table="applications";
    protected $fillable = [
        'app_code',
        'app_name',
        'app_description',
        'app_route_frontend_web',
        'app_route_frontend_mobile',
        'app_icon_class',
        'app_icon_image',
    ];
}
