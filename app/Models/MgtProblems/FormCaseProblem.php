<?php

namespace App\Models\MgtProblems;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormCaseProblem extends Model
{
    use HasFactory;
    protected $table = "form_case_problems";
    protected $fillable = [
        'problem_id',
        'case',
        'case_type',
        'quantity',
        'decision',
        'note',
        'image',
    ];
}
