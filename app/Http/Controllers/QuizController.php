<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use App\Models\Subject;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quiz = Quiz::all();
        
        return view('quiz.index',compact('quiz'));
    }
    
    public function create(){
        $standards = Standard::select('id', 'name')->get()->toArray();
        $subjects = Subject::all();
        $chapters = Chapter::all();
        $courses = Course::all();
        return view('quiz.create',compact('standards','chapters','subjects','courses'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'quiz_title' => 'required|string',
            'quiz_type' => 'required',
            'quiz_desc' => 'required',
            'total_quiz_time' => 'required',
            // 'courses' => 'required',
            'quiz_standard' => 'required_if:quiz_type,STWQ',
            'quiz_subject' => 'required_if:quiz_type,SWQ',
            'quiz_chapter' => 'required_if:quiz_type,CWQ',
        ]);

        if($request->quiz_type == 'STWQ'){
            // yha se hum vo sare courses pic krlenge jisme ye standard_id store
        }

        $quiz = new Quiz;
        $quiz->title = $request->quiz_title;
        $quiz->type = $request->quiz_type;
        $quiz->subject_id = isset($request->quiz_subject) ? $request->quiz_subject : null;
        $quiz->standard_id = isset($request->quiz_standard) ? $request->quiz_standard : null;
        $quiz->chapter_id = isset($request->quiz_chapter) ? $request->quiz_chapter : null;
        $quiz->quiz_desc = $request->quiz_desc;
        $quiz->marks_type = isset($request->marks_type) ? 1 : 0;
        $quiz->manual_marks = isset($request->marks_type) ? $request->manual_marks : 0;
        $quiz->negative_marking_type = isset($request->negative_marking_type) ? 1 : 0;
        $quiz->negative_marking = isset($request->negative_marking_type) ? $request->negative_marking : 0;
        $quiz->total_quiz_time = $request->total_quiz_time;
        $quiz->scheduled_at = $request->scheduler;
        $quiz->is_published = ($request->is_published == "on") ? 1 : 0;
        // if($request->courses && count($request->courses) > 0){
        //     $quiz->course_id = json_encode($request->courses,true);
        // }
        $quiz->save();
        return redirect()->route('quizes.add-questions', ['id' => $quiz->id])
                ->with('success', 'Quiz created successfully');
    }

    public function edit($id){
        $standards = Standard::select('id', 'name')->get()->toArray();
        $subjects = Subject::all();
        $chapters = Chapter::all();
        $courses = Course::all();
        $quiz = Quiz::findOrFail($id);
        
        return view('quiz.edit',compact('standards','chapters','subjects','courses','quiz'));
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'quiz_title' => 'required|string',
            'quiz_type' => 'required',
            'quiz_desc' => 'required',
            'total_quiz_time' => 'required',
            // 'courses' => 'required',
            'quiz_standard' => 'required_if:quiz_type,STWQ',
            'quiz_subject' => 'required_if:quiz_type,SWQ',
            'quiz_chapter' => 'required_if:quiz_type,CWQ',
        ]);
        $quiz = Quiz::findOrFail($id);
        $quiz->title = $request->quiz_title;
        $quiz->type = $request->quiz_type;
        $quiz->subject_id = isset($request->quiz_subject) ? $request->quiz_subject : null;
        $quiz->standard_id = isset($request->quiz_standard) ? $request->quiz_standard : null;
        $quiz->chapter_id = isset($request->quiz_chapter) ? $request->quiz_chapter : null;
        $quiz->quiz_desc = $request->quiz_desc;
        $quiz->marks_type = isset($request->marks_type) ? 1 : 0;
        $quiz->manual_marks = isset($request->marks_type) ? $request->manual_marks : 0;
        $quiz->negative_marking_type = isset($request->negative_marking_type) ? 1 : 0;
        $quiz->negative_marking = isset($request->negative_marking_type) ? $request->negative_marking : 0;
        $quiz->total_quiz_time = $request->total_quiz_time;
        $quiz->scheduled_at = $request->scheduler;
        $quiz->is_published = ($request->is_published == "on") ? 1 : 0;

        // if(count($request->courses) > 0){
        //     $quiz->course_id = json_encode($request->courses,true);
        // }
        $quiz->save();
        return redirect()->route('quizes.index', ['id' => $quiz->id])
                ->with('success', 'Quiz Updated successfully');
    }

    public function addQuestions($id){
        $quiz = Quiz::findOrFail($id);
        $standards = Standard::select('id', 'name')->get()->toArray();

        return view('quiz.add_questions',compact('quiz','standards'));
    }

    public function storeQuestions(){
        dd("123");
    }

    public function destroy(Quiz $quize)
    {
        // dd($quize);
        // Check if the quiz exists
        //if (!$quize) {
          //  return redirect()->route('quizes.index')->with('error', 'Quiz not found');
        //}

        // Delete the quiz
        //if($quize->delete()){
          // return redirect()->route('quizes.index')->with('success', 'Quiz deleted successfully');
       // }else{
          //return redirect()->route('quizes.index')->with('success', 'Kindly remove its associated questions first.');
        //}

if ($quize->questions($quize->id) > 0) {
    return redirect()->route('quizes.index')->with('error', 'Kindly remove the associated questions first.');
} else {
    if ($quize->delete()) {
        return redirect()->route('quizes.index')->with('success', 'Quiz deleted successfully');
    } else {
        return redirect()->route('quizes.index')->with('error', 'An error occurred while deleting the quiz.');
    }
}
    }
}
