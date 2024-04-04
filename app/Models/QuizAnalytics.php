<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAnalytics extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'quiz_analytics';
    protected $dates = ['deleted_at'];
}
