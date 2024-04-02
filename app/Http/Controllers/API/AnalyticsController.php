<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppUsage;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use App\Models\PlayedTopics;
use Illuminate\Support\Facades\Auth;

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
}
