<?php

namespace App\Models\HR\Request;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    protected $table ="user_request_leaves";
    protected $fillable=[
        'request_hash_id',
        'number_unix',
        'request_type',
        'request_user_id',
        'request_user_empid',
        'request_approval',
        'request_date',
        'request_time_leaving',
        'request_time_returning',
        'security_check_leave',
        'security_check_return',
        'request_reason',
        'photo_approval',
        'status',
    ];

}
