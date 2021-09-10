<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLetter extends Model
{
    use HasFactory;
    protected $table = "company_letters";
    protected $fillable = [
        'doc_number',
        'number_of_letter',
        'company_code',
        'department_code',
        'type_id',
        'category_id',
        'area_code',
        'title',
        'upload_path_document',
        'confidential'
    ];
}
