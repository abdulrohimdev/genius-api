<?php

namespace App\Models\MgtProblems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormProblem extends Model
{
    use HasFactory;
    protected $table="management_form_problems";
    protected $fillable = [
        'company_code',
        'management_area',
        'location',
        'process',
        'type',
        'product',
        'line',
        'create_by',
    ];

}
