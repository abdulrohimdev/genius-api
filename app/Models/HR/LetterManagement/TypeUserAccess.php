<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeUserAccess extends Model
{
    use HasFactory;
    protected $table ="letter_user_types";
    protected $fillable = [
        'secret_key','type_id'
    ];

}
