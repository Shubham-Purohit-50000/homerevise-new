<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $fillable = ['name', 'state_id', 'folder_name'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}