<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quiz extends Model
{
    use HasFactory;
    protected $table = 'quiz';

    public function questions($id){
        $questions = DB::table('quiz_questions')->where('quiz_id','=',$id)->get();
        return count($questions);
    }

    public function totalQuestions($id){
        $questions = DB::table('quiz_questions')->where('quiz_id','=',$id)->pluck('question_id')->toArray();
        return $questions;
    }

    public function marks($id){
        // dd($id);
        $quizQuestionIds = DB::table('quiz_questions')->where('quiz_id', $id)->pluck('question_id')->toArray();
        $questions = Questions::whereNull('deleted_at')
                    ->whereIn('id', $quizQuestionIds)
                    ->get();
        $count = 0;
        if(count($questions) > 0){
            foreach($questions as $item){
                // dd($item->correct_marks);
                $count = $count + $item->correct_marks;
            }        
        }
        return $count;
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
