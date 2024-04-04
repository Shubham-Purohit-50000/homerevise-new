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
        
        $validator = Validator::make($request->all(), [ 
            'subject' => 'required',
            'chapter' => 'required',
            'topic' => 'required',
            'duration_minutes' => 'required',
            'total_topics' => 'required', 
        ]);
    
        // Check if the validation fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }
        $playedTopics = new PlayedTopics;
        $playedTopics->user_id = auth()->user()->id;
        $playedTopics->subject = $request->subject;
        $playedTopics->chapter = $request->chapter;
        $playedTopics->topic = $request->topic;
        $playedTopics->duration_minutes = $request->duration_minutes;
        $playedTopics->total_topics = $request->total_topics;

        $playedTopics->save();
        $success['status'] = true;
        return $this->sendResponse($success, 'Played Topics created Successfully.');
    }

    public function getPlayedTopics(Request $request){
        $playedTopics = PlayedTopics::where('user_id',auth()->user()->id)->latest()->get();
        return $this->sendResponse($playedTopics, 'Played Topics Fetched Successfully.');
    }

    public function pushQuizAnalytics(Request $request){

        $validator = Validator::make($request->all(), [ 
            'quiz_name' => 'required',
            'total_questions' => 'required',
            'questions_attempted' => 'required',
            'marks_earned' => 'required',
            'total_marks' => 'required',
            'right_questions' => 'required',
            'wrong_questions' => 'required',
        ]);
    
        // Check if the validation fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 422);
        }
        $quizAnalytics = new QuizAnalytics;
        $quizAnalytics->user_id = auth()->user()->id;
        $quizAnalytics->subject = $request->subject;
        $quizAnalytics->quiz_name = $request->quiz_name;
        $quizAnalytics->total_questions = $request->total_questions;
        $quizAnalytics->questions_attempted = $request->questions_attempted;
        $quizAnalytics->marks_earned = $request->marks_earned;
        $quizAnalytics->total_marks = $request->total_marks;
        $quizAnalytics->right_questions = $request->right_questions;
        $quizAnalytics->wrong_questions = $request->wrong_questions;
        $quizAnalytics->save();
        $success['status'] = true;
        return $this->sendResponse($success, 'Quiz Analytics created Successfully.');
    }
    public function getQuizAnalytics(Request $request){
        $quizAnalytics = QuizAnalytics::where('user_id',auth()->user()->id)->latest()->get();
        return $this->sendResponse($quizAnalytics, 'Quiz Analytics Fetched Successfully.');
    }
}
