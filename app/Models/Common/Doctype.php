<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctype extends Model
{
    use HasFactory;
    protected $table = "approval_document_status";
    protected $fillable = [
        'doc_number',
        'doctype',
        'status',
        'create_by',
        'approved_by',
        'approval_note',
    ];
}
