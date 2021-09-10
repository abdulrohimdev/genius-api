<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table="companies";
    protected $fillable=[
        'company_code',
        'company_name',
        'description',
        'address',
        'city',
        'province',
        'country',
        'zip_code',
        'telp',
        'fax',
        'email',
        'website',
    ];
}
