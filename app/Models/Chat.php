<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_group',
        'name',
        'allow_messages',
        'avatar',
        'user_id_sender',
        'user_id_recipient',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}