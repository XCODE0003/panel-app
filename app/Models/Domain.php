<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'status',
        'user_id',
        'cloudflare_zone_id',
        'template_id',
        'ns_records',
    ];

    protected $casts = [
        'ns_records' => 'array',
    ];
}
