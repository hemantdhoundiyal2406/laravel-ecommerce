<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactQuery extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'is_read', 'is_replied'];

    protected $casts = [
        'is_read' => 'boolean',
        'is_replied' => 'boolean',
    ];
}
