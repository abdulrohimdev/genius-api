<?php

namespace App\Models\Common\News;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class News extends Model
{
    use HasFactory;
    protected $table="news";
    protected $fillable=[
        'title',
        'subtitle',
        'description',
        'filter_company',
        'image_uri',
        'posted_date',
        'posted_by',
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
}
