<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryUserAccess extends Model
{
    use HasFactory;
    protected $table ="letter_user_categories";
    protected $fillable = [
        'secret_key','type_id','category_id'
    ];

}
