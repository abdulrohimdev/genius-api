<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryOfLetter extends Model
{
    use HasFactory;
    protected $table ="category_of_letters";
    protected $fillable=[
        'category_letter',
        'type_of_letter_id'
    ];

}
