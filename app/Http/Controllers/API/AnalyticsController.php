<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppUsage;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\PlayedTopics;
use App\Models\QuizAnalytics;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AnalyticsController extends BaseController
{
    public function pushAppAnalytics(Request $request){

        $appUsage = AppUsage::where('user_id', auth()->user()->id)->first();   
        if($appUsage){
            $appUsage->app_usage_time = $request->app_usage_time;
            $appUsage->save();
            return $this->sendResponse($appUsage, 'App Usage Updated Successfully.');
        }

        $appUsage = new AppUsage;
        $appUsage->user_id = auth()->user()->id;
        $appUsage->app_usage_time = $request->app_usage_time;

        $appUsage->save();

        return $this->sendResponse($appUsage, 'App Usage Created Successfully.');

    }
    public function getAppAnalytics(Request $request){
        
        $appUsage = AppUsage::where('user_id', auth()->user()->id)->latest()->first();                

        return $this->sendResponse($appUsage, 'App Usage Fetch Successfully.');

    }

    public function pushPlayedTopics(Request $request){
        
        // $validator = Validator::make($request->all(), [ 
        //     'subject' => 'required',
        //     'chapter' => 'required',
        //     'topic' => 'required',
        //     'duration_minutes' => 'required',
        //     'total_topics' => 'required', 
        // ]);
        $requestData = $request->all();
 
        // Check if the validation fails
        // if ($validator->fails()) {
        //     return $this->sendError('Validation Error', $validator->errors(), 422);
        // }
        foreach($requestData as $data){ 
            $playedTopics = new PlayedTopics;
            $playedTopics->user_id = auth()->user()->id;
            $playedTopics->subject = $data['subject'];
            $playedTopics->chapter = $data['chapter'];
            $playedTopics->topic = $data['topic'];
            $playedTopics->duration_minutes = $data['duration_minutes'];
            $playedTopics->total_topics = $data['total_topics'];

            $playedTopics->save();
        }
        $success['status'] = true;
        return $this->sendResponse($success, 'Played Topics created Successfully.');
    }

    public function getPlayedTopics(Request $request){
        $playedTopics = PlayedTopics::where('user_id',auth()->user()->id)->latest()->get();
        return $this->sendResponse($playedTopics, 'Played Topics Fetched Successfully.');
    }

    public function pushQuizAnalytics(Request $request){

        // $validator = Validator::make($request->all(), [ 
        //     'quiz_name' => 'required',
        //     'total_questions' => 'required',
        //     'questions_attempted' => 'required',
        //     'marks_earned' => 'required',
        //     'total_marks' => 'required',
        //     'right_questions' => 'required',
        //     'wrong_questions' => 'required',
        // ]);
    
        // // Check if the validation fails
        // if ($validator->fails()) {
        //     return $this->sendError('Validation Error', $validator->errors(), 422);
        // }
        
        $requestData = $request->all();
        foreach($requestData as $data){
            $quizAnalytics = new QuizAnalytics;
            $quizAnalytics->user_id = auth()->user()->id;
            $quizAnalytics->subject = $data['subject'];
            $quizAnalytics->quiz_name = $data['quiz_name'];
            $quizAnalytics->total_questions = $data['total_questions'];
            $quizAnalytics->questions_attempted = $data['questions_attempted'];
            $quizAnalytics->marks_earned = $data['marks_earned'];
            $quizAnalytics->total_marks = $data['total_marks'];
            $quizAnalytics->right_questions = $data['right_questions'];
            $quizAnalytics->wrong_questions = $data['wrong_questions'];
            $quizAnalytics->save();
        }


        $success['status'] = true;
        return $this->sendResponse($success, 'Quiz Analytics created Successfully.');
    }
    public function getQuizAnalytics(Request $request){
        $quizAnalytics = QuizAnalytics::where('user_id',auth()->user()->id)->latest()->get();
        return $this->sendResponse($quizAnalytics, 'Quiz Analytics Fetched Successfully.');
    }


    public function storeDatabase(Request $request) {
        $rules = [
            'database' => 'required|string',
            'database_url' => 'required|file'
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $user = auth()->user();
    
        if ($request->hasFile('database_url')) {
            $file = $request->file('database_url');
    
            // Check if the file is valid
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload.'
                ], 400);
            }
    
            // Check if the file has content
            if ($file->getSize() == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'File is empty.'
                ], 400);
            }
    
            // Store the file
            $filePath = $file->store('user/database', 'public');
    
            $data = [
                'json' => $request->database,
                'file_path' => $filePath
            ];
    
            $user->update([
                'database' => json_encode($data),
            ]);
    
            return $this->sendResponse($data, 'Data Updated Successfully.');
        } else {
            return $this->sendResponse([], 'No file uploaded.', 400);
        }
    }
    
    
}
