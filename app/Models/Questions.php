<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questions extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'questions';

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
