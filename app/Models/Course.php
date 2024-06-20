<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'standard_id',
        'subject_id',
        'duration',
        'status',
        'folder_name',
        'device_type',
        'access_count',
    ];

    protected $casts   =[
        'standard_id'=>'array',
        'subject_id'=>'array'
    ] ;

    public function standards()
    {
        return Standard::whereIn('id', $this->standard_id)->get();
    }
    public function subjects()
    {
        return Subject::whereIn('id', $this->subject_id)->get();
    }


//    public function standard()
//    {
//        return $this->belongsTo(Standard::class);
//    }
//
//    public function subject()
//    {
//        return $this->belongsTo(Subject::class);
//    }

    public function activation()
    {
        return $this->hasMany(Activation::class);
    }
}
