<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionMobile extends Model
{
    use HasFactory;
    protected $table="mobile_versions";
    protected $fillable = [
        'version'
    ];
}
