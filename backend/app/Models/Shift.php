<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'line_user_id',
        'shift_date',
        'shift_type',
        'start_time',
        'end_time',
        'lectures'
    ];

    protected $casts = [
        'lectures' => 'array',
        'shift_date' => 'date'
    ];

    public function lineUser()
    {
        return $this->belongsTo(LineUser::class, 'line_user_id', 'line_user_id');
    }
}
