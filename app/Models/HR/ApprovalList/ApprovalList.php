<?php

namespace App\Models\HR\ApprovalList;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalList extends Model
{
    use HasFactory;
    protected $table ="approval_lists";
    protected $fillable=[
        'empid',
        'user_id',
        'fullname',
        'divisi',
        'department',
        'company',
        'photo',
    ];

}
