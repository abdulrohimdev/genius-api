<?php

namespace App\Models\HR\LetterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaOfLetter extends Model
{
    use HasFactory;
    protected $table ="area_of_letters";
    protected $fillable=[
        'area_id',
        'area_name',
    ];

}
