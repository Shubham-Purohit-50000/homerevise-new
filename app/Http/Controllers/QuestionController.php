<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Standard;
use App\Models\Subject;
use App\Models\Questions;
use App\Models\Chapter;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index(Request $request){

        $questions = Questions::all();
        return view('questions.index', compact('questions'));

    }

    public function create(){
        $questionTypeArray = [
            array(
                'name' => 'Select Question Type',
                'code' => '',
            ),
            array(
                'name' => 'Multiple Choice Single Answere (MSA)',
                'code' => 'MSA',
            ),
            array(
                'name' => 'True or False (TOF)',
                'code' => 'TOF',
            ),
            array(
                'name' => 'Fill in the Blanks (FIB)',
                'code' => 'FIB',
            ),
            array(
                'name' => 'Short Answer Question (SAQ)',
                'code' => 'SAQ',
            ),
        ];
        $standards = Standard::select('id', 'name')->get()->toArray();

        return view('questions.create', compact('questionTypeArray', 'standards'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'question_type' => 'required|string',
            'standards' => 'required',
            'subject' => 'required',
            'chapter' => 'required',
            'question' => 'required',
            'correct_ans' => 'required',
            'correct_marks' => 'required|numeric',
        ]);

        $question = new Questions;
        $question->question_type = $request->question_type;
        $question->questions = $request->question;
        $question->standard_id = $request->standards;
        $question->subject_id = $request->subject;
        $question->chapter_id = $request->chapter;
        $question->topic_id = $request->topic ? $request->topic : null;
        $question->options = json_encode($request->options);
        $question->correct_answer = $request->correct_ans;
        $question->correct_marks = $request->correct_marks;
        $question->explanation = $request->explanation ? $request->explanation : null;

        $question->save();

        return redirect()->route('questions.index')
        ->with('success', 'Question created successfully');
    }

    public function edit(Questions $question)
    {
        $questionTypeArray = [
            array(
                'name' => 'Select Question Type',
                'code' => '',
            ),
            array(
                'name' => 'Multiple Choice Single Answere (MSA)',
                'code' => 'MSA',
            ),
            array(
                'name' => 'True or False (TOF)',
                'code' => 'TOF',
            ),
            array(
                'name' => 'Fill in the Blanks (FIB)',
                'code' => 'FIB',
            ),
            array(
                'name' => 'Short Answer Question (SAQ)',
                'code' => 'SAQ',
            ),
        ];
        $standards = Standard::select('id', 'name')->get()->toArray();
        return view('questions.edit', compact('question','questionTypeArray','standards'));
    }

    public function update(Request $request, $id){

        $question = Questions::findOrFail($id);

        $question->question_type = $request->question_type;
        $question->questions = $request->question;
        $question->standard_id = $request->standards;
        $question->subject_id = $request->subject;
        $question->chapter_id = $request->chapter;
        $question->topic_id = $request->topic ? $request->topic : null;
        $question->options = json_encode($request->options);
        $question->correct_answer = $request->correct_ans;
        $question->correct_marks = $request->correct_marks;
        $question->explanation = $request->explanation ? $request->explanation : null;

        $question->update();

        return redirect()->route('questions.index')
        ->with('success', 'Question updated successfully');
    }
    public function getSubjects($id){
        $subjects = Subject::select('id', 'name')->where('standard_id', $id)->get()->toArray();
        return response()->json(['data' => $subjects]);
    }

    public function getChapters($id){
        $chapters = Chapter::select('id', 'name')->where('subject_id', $id)->get()->toArray();
        return response()->json(['data' => $chapters]);
    }

    public function getTopics($id){
        $topics = Topic::select('id', 'heading')->where('chapter_id', $id)->get()->toArray();
        return response()->json(['data' => $topics]);
    }

    public function destroy(Questions $question)
    {
        $question->delete();

        return redirect()->route('questions.index')
            ->with('success', 'Question deleted successfully');
    }

    public function fetchQuestions(Request $request){

        $quizId = $request->quiz_id;

        $quizQuestionIds = DB::table('quiz_questions')->where('quiz_id', $quizId)->pluck('question_id')->toArray();

        $questionsQuery = Questions::where('standard_id', $request->standard_id);

        if ($request->chapter_id) {
            $questionsQuery->where('chapter_id', $request->chapter_id);
        }
        if ($request->topic_id) {
            $questionsQuery->where('topic_id', $request->topic_id);
        }
        if ($request->subject_id) {
            $questionsQuery->where('subject_id', $request->subject_id);
        }


        // $questions = $questionsQuery->whereNull('deleted_at')->get();

        $questions = $questionsQuery->whereNull('deleted_at')
                ->whereNotIn('id', $quizQuestionIds)
                ->get();
        // dd($questions);

        // dd($questionsArray);
        return response()->json(['success' => true, 'questions' => $questions]);
    }

    public function viewQuestions(Request $request){
        $quizQuestionIds = DB::table('quiz_questions')->where('quiz_id', $request->quiz_id)->pluck('question_id')->toArray();
        $questions = Questions::whereNull('deleted_at')
                    ->whereIn('id', $quizQuestionIds)
                    ->get();

        return response()->json(['success' => true, 'questions' => $questions]);


    }
    public function addQuizQuestions(Request $request){
        $data = [
            "quiz_id" => $request->quiz_id,
            "question_id" => $request->question_id
        ];
        $insert = DB::table('quiz_questions')->insert($data);

        if($insert){
            return response()->json(['success' => true, 'message' => 'Question Added Successfully']);
        }
    }

    public function removeQuizQuestions(Request $request){
        $data = [
            "quiz_id" => $request->quiz_id,
            "question_id" => $request->question_id
        ];
        $query = DB::table('quiz_questions')->where('quiz_id','=',$request->quiz_id)->where('question_id','=',$request->question_id)->delete();

        if($query){
            return response()->json(['success' => true, 'message' => 'Question Removed Successfully']);
        }
    }

    public function bulkUpload(Request $request){
        if($request->all('questionsFile')['questionsFile']){
            if($request->all('questionsFile')['questionsFile']){
                $file = $request->file('questionsFile');
                $spreadsheet = IOFactory::load($file);
                $worksheet = $spreadsheet->getActiveSheet();
                $columns = [];
                foreach ($worksheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $rowData = [];
                    foreach ($cellIterator as $cell) {
                        $rowData[] = $cell->getValue();
                    }
                    $columns[] = $rowData;
                }
                $result = array_slice($columns, 1);

                if(count($result) > 0){

                    foreach ($result as $column){
                        $question = new Questions;
                        $question->question_type = $column[0];
                        $question->standard_id = $column[1];
                        $question->subject_id = $column[2];
                        $question->chapter_id = $column[3];
                        $question->topic_id = $column[4];
                        $question->questions = $column[5];
                        $question->questionsImage = $column[6];
                        $question->correct_answer = $column[7];
                        $question->correct_marks = $column[8];
                        $options = [];
                        for ($i = 9, $letter = 'A'; isset($column[$i]); $i++, $letter++) {
                            // Ensure the current index exists and is not null
                            if (isset($column[$i])) {
                                // Process $column[$i] here
                                $options[$letter] = $column[$i];
                            }
                        }
                        $question->options = json_encode($options);
                        $question->save();
                    }
                }else{
                    return redirect()->route('questions.index')
                    ->with('success', 'Corrupt Question File Inputs.');
                }
            }

            if($request->all('imageFile')['imageFile']){
                $file = $request->file('imageFile');
                $zipPath = $request->file('imageFile')->store('uploads/zips', 'public');

                // Get the full path to the uploaded zip file
                $zipFilePath = Storage::disk('public')->path($zipPath);

                // Extract the images from the zip file
                $zip = new ZipArchive();
                if ($zip->open($zipFilePath) === true) {
                    $extractPath = public_path('storage/uploads/images/questions/');
                    $zip->extractTo($extractPath);
                    $zip->close();
                }
                return redirect('/admin/questions/import-questions')
                ->with('success', 'Image File Uploaded.');
            }

            return redirect('/admin/questions/import-questions')
            ->with('success', 'Data Imported Successfully.');

        }

        return redirect('/admin/questions/import-questions')
        ->with('success', 'Please Select the File.');
    }

    public function show(){
        return view('questions.import-questions');
    }
}
