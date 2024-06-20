<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    protected $fillable = ['name', 'medium_id', 'folder_name'];

    public function medium()
    {
        return $this->belongsTo(Medium::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function getSubjectById($subjectId)
    {
        // Assuming you have a relationship in Standard model to Subject model called 'subjects'
        return $this->subjects()->find($subjectId);
    }
}
