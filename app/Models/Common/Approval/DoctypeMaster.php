<?php

namespace App\Models\Common\Approval;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctypeMaster extends Model
{
    use HasFactory;
    protected $table = "approval_masterdata_doctypes";
    protected $fillable = [
        'doctype_code','doctype_name'
    ];
}
