<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUserAccess extends Model
{
    use HasFactory;
    protected $table ="letter_user_companies";
    protected $fillable = [
        'secret_key','company_code'
    ];
}
