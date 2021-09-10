<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confidential extends Model
{
    use HasFactory;
    protected $table="letter_user_confidentials";
    protected $fillable = [
        'username','confidential'
    ];
}
