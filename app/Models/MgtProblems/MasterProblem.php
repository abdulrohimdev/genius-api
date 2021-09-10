<?php

namespace App\Models\MgtProblems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProblem extends Model
{
    use HasFactory;
    protected $table="management_master_problems";
    protected $fillable = [
        'company_code',
        'management_area',
        'location',
        'process',
        'type',
        'product',
        'case_type',
        'problem',
    ];
}
