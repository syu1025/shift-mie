<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'line_user_id',
        'message',
        'user_name',
        'start_time',
        'end_time',
        'time_format'
    ];

    protected $dates = [
        'start_time',
        'end_time',
        'created_at',
        'updated_at'
    ];
}
