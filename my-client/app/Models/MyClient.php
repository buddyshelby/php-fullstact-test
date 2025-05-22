<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MyClient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'is_project', 'self_capture', 
        'client_prefix', 'client_logo', 'address',
        'phone_number', 'city'
    ];

    protected $casts = [
        'is_project' => 'string',
    ];
}