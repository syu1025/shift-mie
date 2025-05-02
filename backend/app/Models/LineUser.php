<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineUser extends Model
{
    protected $fillable = [
        'line_user_id',
        'display_name',
        'status_message',
        'picture_url'
    ];

    public function shifts()
    {
        return $this->hasMany(Shift::class, 'line_user_id', 'line_user_id');
    }
}
