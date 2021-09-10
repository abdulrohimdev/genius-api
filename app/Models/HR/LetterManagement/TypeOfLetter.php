<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfLetter extends Model
{
    use HasFactory;
    protected $table ="type_of_letters";
    protected $fillable=[
        'type_of_letter',
        'code_of_type'
    ];
}
