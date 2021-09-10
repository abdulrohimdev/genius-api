<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeptForLetter extends Model
{
    use HasFactory;

    protected $table ="department_for_letters";
    protected $fillable = [
        'company_code',
        'department_code',
        'department_name',
    ];
}
