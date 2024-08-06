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
use App\Models\User;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class QuizController extends BaseController
{
    // public function getQuiz_new(Request $request){
    //     my courses
    //     ke jo standard, subject, chapter h
    //     uske related koi quiz h ki nhi
    // }

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
                $quiz_flag = 0;
                if ($quizItem->standard_id) {
                    $quizStandardId = (string) $quizItem->standard_id;
                    $check = Course::where('id', $course_id)->whereJsonContains('standard_id', (string) $quizStandardId)->exists();
                    if($check){
                        $quiz_flag = 1;
                        $quizItem->standard = Standard::findOrFail($quizItem->standard_id);
                    } 
                } 
                if ($quizItem->subject_id) {
                    $quizSubjectId = (string) $quizItem->subject_id;
                    $check = Course::where('id', $course_id)->whereJsonContains('subject_id', (string) $quizSubjectId)->exists();
                    if($check){
                        $quiz_flag = 1;
                        $quizItem->subject = Subject::findOrFail($quizItem->subject_id);
                        $quizItem->standard = Standard::findOrFail($quizItem->subject->standard_id);
                    }else{
                        // get standard_ids belongs to this subject and if this standard id present in course the valid
                        $check = Course::where('id', $course_id)->whereJsonContains('standard_id', (string) $quizItem->subject->standard_id)->exists();
                        if($check){
                            $quiz_flag = 1;
                            $quizItem->subject = Subject::findOrFail($quizItem->subject_id);
                            $quizItem->standard = Standard::findOrFail($quizItem->subject->standard_id);
                        } 
                    }
                } 
                if ($quizItem->chapter_id) {
                    // $quiz_flag = 1;

                    $quizSubjectId = (string) $quizItem->chapter->subject_id;
                    $check = Course::where('id', $course_id)->whereJsonContains('subject_id', (string) $quizSubjectId)->exists();
                    if($check){
                        $quiz_flag = 1;
                        $quizItem->chapter = Chapter::findOrFail($quizItem->chapter_id);
                        $quizItem->subject = Subject::findOrFail($quizItem->chapter->subject_id);
                        $quizItem->standard = Standard::findOrFail($quizItem->chapter->subject->standard_id);
                    }else{
                        // get standard_ids belongs to this subject and if this standard id present in course the valid
                        $check = Course::where('id', $course_id)->whereJsonContains('standard_id', (string) $quizItem->chapter->subject->standard_id)->exists();
                        if($check){
                            $quiz_flag = 1;
                            $quizItem->chapter = Chapter::findOrFail($quizItem->chapter_id);
                            $quizItem->subject = Subject::findOrFail($quizItem->chapter->subject_id);
                            $quizItem->standard = Standard::findOrFail($quizItem->chapter->subject->standard_id);
                        } 
                    }
                }
                
                // quiz k under user k course id h to question nikalenge
                if ($quiz_flag) {
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

    public function getQuiz_old(Request $request){
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

    public function getOldQuizFormate_old(Request $request){
        $course_ids = [];
        $data = [];
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
    
        if (empty($course_ids)) {
            return $this->sendError('Bad Request.', ['error' => 'Course Id is required for fetching the Quiz data.'], 400);
        }
        
        $quizzes = Quiz::where(function($query) use ($course_ids) {
            foreach ($course_ids as $id) {
                // Assuming the column stores data as JSON and using MySQL's JSON search
                $query->orWhereJsonContains('course_id', $id);
            }
        })->get();
        
        $data = [];
        
        foreach ($quizzes as $quiz) {
            $questions = $quiz->totalQuestions($quiz->id);
            $questions = Questions::whereIn('questions.id', $questions)
                ->join('standards', 'standards.id', '=', 'questions.standard_id')
                ->join('chapters', 'chapters.id', '=', 'questions.chapter_id')
                ->join('subjects', 'subjects.id', '=', 'questions.subject_id')
                ->join('mediums', 'mediums.id', '=', 'standards.medium_id')
                ->join('boards', 'boards.id', '=', 'mediums.board_id')
                ->join('states', 'states.id', '=', 'boards.state_id')
                ->select('questions.id', 'questions.question_type', 'questions.questions', 'questions.options', 'questions.correct_answer', 'questions.correct_marks', 'questions.explanation', 'standards.id as standard_id', 'standards.name as standard_name', 'mediums.id as medium_id', 'mediums.name as medium_name', 'subjects.id as subject_id', 'subjects.name as subject_name', 'chapters.id as chapter_id', 'chapters.name as chapter_name', 'boards.id as board_id', 'boards.name as board_name')
                ->get();
        
            foreach ($questions as $array) {
                $array->options = array_map(function ($key, $value) {
                    return (object) [$key => $value];
                }, array_keys(json_decode($array->options, true)), json_decode($array->options, true));
            }
        
            $quizData = $quiz->toArray();
            $quizData['questions'] = $questions->toArray();
            $data[] = $quizData;
        }
        // dd($data);
        return $this->sendResponse($data, 'Data fetched Successfully.');
    }

    public function getOldQuizFormate(Request $request)
    {
        $data = [];
        $course_ids = [];
        
        // Check if user is authenticated
        if (auth()->user()) {
            $activatedCourses = auth()->user()->activation;
            if (count($activatedCourses) > 0) {
                foreach ($activatedCourses as $item) {
                    $course_ids[] = (string)$item->course_id;
                }
            }
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'User is not loggedIn'], 401);
        }

        if (empty($course_ids)) {
            return $this->sendError('Bad Request.', ['error' => 'Course Id is required for fetching the Quiz data.'], 400);
        }

        $quizzes = Quiz::all();

        foreach ($course_ids as $course_id) {
            foreach ($quizzes as $quizItem) {
                $quiz_flag = false;

                if ($quizItem->standard_id) {
                    $quizStandardId = (string) $quizItem->standard_id;
                    $check = Course::where('id', $course_id)->whereJsonContains('standard_id', $quizStandardId)->exists();
                    if ($check) {
                        $quiz_flag = true;
                        $quizItem->standard = Standard::findOrFail($quizItem->standard_id);
                    }
                }

                if ($quizItem->subject_id) {
                    $quizSubjectId = (string) $quizItem->subject_id;
                    $check = Course::where('id', $course_id)->whereJsonContains('subject_id', $quizSubjectId)->exists();
                    if ($check) {
                        $quiz_flag = true;
                        $quizItem->subject = Subject::findOrFail($quizItem->subject_id);
                        $quizItem->standard = Standard::findOrFail($quizItem->subject->standard_id);
                    } else {
                        $check = Course::where('id', $course_id)->whereJsonContains('standard_id', (string) $quizItem->subject->standard_id)->exists();
                        if ($check) {
                            $quiz_flag = true;
                            $quizItem->subject = Subject::findOrFail($quizItem->subject_id);
                            $quizItem->standard = Standard::findOrFail($quizItem->subject->standard_id);
                        }
                    }
                }

                if ($quizItem->chapter_id) {
                    $quizSubjectId = (string) $quizItem->chapter->subject_id;
                    $check = Course::where('id', $course_id)->whereJsonContains('subject_id', $quizSubjectId)->exists();
                    if ($check) {
                        $quiz_flag = true;
                        $quizItem->chapter = Chapter::findOrFail($quizItem->chapter_id);
                        $quizItem->subject = Subject::findOrFail($quizItem->chapter->subject_id);
                        $quizItem->standard = Standard::findOrFail($quizItem->chapter->subject->standard_id);
                    } else {
                        $check = Course::where('id', $course_id)->whereJsonContains('standard_id', (string) $quizItem->chapter->subject->standard_id)->exists();
                        if ($check) {
                            $quiz_flag = true;
                            $quizItem->chapter = Chapter::findOrFail($quizItem->chapter_id);
                            $quizItem->subject = Subject::findOrFail($quizItem->chapter->subject_id);
                            $quizItem->standard = Standard::findOrFail($quizItem->chapter->subject->standard_id);
                        }
                    }
                }

                if ($quiz_flag) {
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
                            $optionsArray[] = (object)[$key => $value];
                        }
                        $question->options = $optionsArray;
                    }

                    $quizData = $quizItem->toArray();
                    $quizData['questions'] = $questions->toArray();
                    $data[] = $quizData;
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

