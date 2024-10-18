<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'domain',
        'ip',
        'user_agent',
        'address',
        'balance',
        'country',
    ];
}