<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationRuleModel extends Model
{
    use HasFactory;
    protected $table ="application_rules";
    protected $fillable = [
        'application',
        'class_access',
        'class_value',
        'is_query',
    ];
}
