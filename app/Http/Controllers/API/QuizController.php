<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Questions;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Standard;
use App\Models\Chapter;
use App\Models\QuizResult;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class QuizController extends BaseController
{
     public function getQuiz(Request $request){
        $data = [];
        $course_ids = [];
        if(auth()->user()){
            $activatedCourses = auth()->user()->activation;
            if(count($activatedCourses) > 0){
                foreach($activatedCourses as $item){ 
                    $course_ids[] = "$item->course_id";
                }
            } 
        }else{
            return $this->sendError('Unauthorised.', ['error' => 'User is not logedIn'], 401);
        }
        $quiz = Quiz::all();
        foreach ($course_ids as $course_id) {
            foreach ($quiz as $quizItem) {  
                if ($quizItem->standard_id) {
                    $quizItem->standard = Standard::findOrFail($quizItem->standard_id);
                } 
                if ($quizItem->subject_id) {
                    $quizItem->subject = Subject::findOrFail($quizItem->subject_id);
                    $quizItem->standard = Standard::findOrFail($quizItem->subject->standard_id);
                } 
                if ($quizItem->chapter_id) {
                    $quizItem->chapter = Chapter::findOrFail($quizItem->chapter_id);
                    $quizItem->subject = Subject::findOrFail($quizItem->chapter->subject_id);
                    $quizItem->standard = Standard::findOrFail($quizItem->subject->standard_id);
                }     
                $courses = [];
                foreach (json_decode($quizItem->course_id) as $id) { 
                    $courses[] = Course::findOrFail(intval($id));
                }
                $quizItem->courses = $courses;
                
                if (in_array($course_id, json_decode($quizItem->course_id))) {
                    $questions = $quizItem->totalQuestions($quizItem->id);
                    $questions = Questions::whereIn('questions.id', $questions)
                        ->join('standards', 'standards.id', '=', 'questions.standard_id')
                        ->join('chapters', 'chapters.id', '=', 'questions.chapter_id')
                        ->join('subjects', 'subjects.id', '=', 'questions.subject_id')
                        ->join('mediums', 'mediums.id', '=', 'standards.medium_id')
                        ->join('boards', 'boards.id', '=', 'mediums.board_id')
                        ->join('states', 'states.id', '=', 'boards.state_id')
                        ->select(
                            'questions.id',
                            'questions.question_type',
                            'questions.questions',
                            'questions.options',
                            'questions.correct_answer',
                            'questions.correct_marks',
                            'questions.explanation',
                            'questions.questionsImage',
                            'standards.id as standard_id',
                            'standards.name as standard_name',
                            'mediums.id as medium_id',
                            'mediums.name as medium_name',
                            'subjects.id as subject_id',
                            'subjects.name as subject_name',
                            'chapters.id as chapter_id',
                            'chapters.name as chapter_name',
                            'boards.id as board_id',
                            'boards.name as board_name'
                        )
                        ->get();
        
                    foreach ($questions as $question) {
                        $options = json_decode($question->options, true);
                        $optionsArray = [];
                        foreach ($options as $key => $value) {
                            $optionsArray[] = (object) [$key => $value];
                        }
                        $question->options = $optionsArray;
                    }
        
                    $quizItem->questions = $questions->toArray();
                    if($quizItem->type == "STWQ"){
                        $data['Standard_wise_quiz'][] = $quizItem;                
                    }
                    if($quizItem->type == "SWQ"){
                        $data['Subject_wise_quiz'][] = $quizItem;                
                    }
                    if($quizItem->type == "CWQ"){
                        $data['Chapter_wise_quiz'][] = $quizItem;                
                    }
                }
            }
        }
        return $this->sendResponse($data, 'Data fetched Successfully.');

    }

    public function submitQuiz(Request $request){
        $jsonData = $request->getContent();
        $data = json_decode($jsonData, true);
        if(count($data) > 0){
            foreach($data as $key=>$value){
                if($key != ""){
                    $length = 24;
                    $alphanumericString = Str::random($length);
                    $positions = 4;
                    $session_code = $this->createUniqueQuizSession($alphanumericString, $positions);
                    $quiz_session = array(
                        "session_code" => $session_code,
                        "user_id" => auth()->user()->id,
                        "quiz_id" => $key
                    );
                    $exam_session = DB::table('quiz_sessions')->insert($quiz_session);

                    foreach($value as $question_id => $item){
                        $item['question_id'] = $question_id;
                        $quizResult = new QuizResult;
                        $quizResult->session_code = $session_code;
                        $quizResult->result = json_encode($item);
                        $quizResult->save();
                    }
                }else{
                    return $this->sendError('Unappropriate data recieved.', ['error' => 'Data formate is not valid.'], 401);
                }
            }
            return $this->sendResponse('Success', ['message' => 'Thank you. Your Result has been Generated Successfully.'], 401);

        }
        return $this->sendError('Unappropriate data recieved.', ['error' => 'Data formate is not valid.'], 401);
    }


    public function getAnalytics(Request $request){
        if($request->quiz_id){
            $analytics = Quiz::join('quiz_sessions', 'quiz_sessions.quiz_id', '=', 'quiz.id')
                        ->where('quiz.id', $request->quiz_id)
                        ->select('quiz.title','quiz.type', 'quiz_sessions.session_code')
                        ->get();
            foreach($analytics as $item){
                $exam_results = DB::table('quiz_results')->where('session_code',$item->session_code)->get();
                $item['results'] = $exam_results;
            }
            return $this->sendResponse($analytics, 'Data fetched Successfully.');
        }else{
            return $this->sendError('Unappropriate data recieved.', ['error' => 'Quiz Id is not valid.'], 500);
        }
    }

    private function createUniqueQuizSession($inputString, $positions) {
        $inputString = preg_replace('/[^a-zA-Z0-9]/', '', $inputString);
        $result = preg_replace("/(.{4})/", "$1-", $inputString);

        return trim($result, '-');
    }

}

