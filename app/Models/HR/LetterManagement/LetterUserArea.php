<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterUserArea extends Model
{
    use HasFactory;
    protected $table = "letter_user_areas";
    protected $fillable = [
        'secret_key','area_code'
    ];
}
