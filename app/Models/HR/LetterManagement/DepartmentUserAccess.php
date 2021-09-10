<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentUserAccess extends Model
{
    use HasFactory;
    protected $table ="letter_user_departments";
    protected $fillable = [
        'secret_key','company_code','department_code'
    ];
}
