<?php

namespace App\Http\Controllers\API;

use App\Models\Standard;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Exception;
use App\Models\Activation;
use Carbon\Carbon;
use App\Models\Setting;

class CourseManagementController extends BaseController
{
    public function addKey(Request $request){
        $user = auth()->user();
        $data = $request->validate([
            'activation_key' => 'required|exists:activations,activation_key',
        ]);

        $activation = Activation::where('activation_key', $request->activation_key)->first();
        if($activation->user_id == null){
            $activation->user_id = $user->id;
            $activation->activation_time = Carbon::now();
            $activation->expiry_date = Carbon::now()->addMonths($activation->course->duration);
            $activation->save();
        }else{
            return $this->sendError('Unauthorised.', ['error' => 'This activation key is already in use.'], 401);
        }

        $data['status'] = true;

        return $this->sendResponse($data, 'Course activation Key added Successfully.');
    }


    public function course(Request $request) {
        // Get the authenticated user
        $user = auth()->user();

        // Get all activation keys associated with the user
        $activations = $user->activation;

        // Check if the user has any activation keys
        if ($activations->isEmpty()) {
            return $this->sendError('Unauthorised.', ['error' => 'No activation keys found for this user.'], 401);
        }

        // Initialize an array to store activation details for all keys
        $activationDetails = [];
        $activationDetails['courses_count'] = $activations->count();

        // Loop through each activation key and load related models
        $activationDetails['courses'] = $activations->map(function ($activation) {
            $activationData = [];
//            dd($activation->course->subjects());

            // Load subjects if available
            if ($activation->course->subject_id!==null) {

                $activation->expiry_date = $activation->course->duration == 0 ? null : $activation->expiry_date;
                $activation->course->duration = $activation->course->duration == 0 ? null : $activation->course->duration;

                $activationData = [
                    'activations'=>$activation,
                    'name' => $activation->course['name'],
                    'folder_name' => $activation->course['folder_name'],
                    'access_count' => $activation->course['access_count'],
                    'device_type' => $activation->course['device_type'],
                    'duration' => $activation->course['duration'] !== 0 ? $activation->course['duration'] : null,
                    'standards' => Standard::whereHas('subjects', function ($query) use ($activation) {
                        $query->whereIn('id', $activation->course->subject_id);
                    })
                        ->with(['subjects' => function ($query) use ($activation) {
                            $query->whereIn('id', $activation->course->subject_id)
                                ->with(['chapters' => function ($query) {
                                    $query->with('topics.subtopics');
                                }]);
                        }])
                        ->get(),


                ];
            } else {

                // Load standards if subjects are not available
                $activationData = [
                    'activations'=>$activation,
                    'name' => $activation->course['name'],
                    'folder_name' => $activation->course['folder_name'],
                    'access_count' => $activation->course['access_count'],
                    'device_type' => $activation->course['device_type'],
                    'duration' => $activation->course['duration'] !== 0 ? $activation->course['duration'] : null,
                    'standards' => $activation->course->standards()->load('subjects.chapters.topics.subtopics'),
                ];

            }

            return $activationData;
        });

        return $this->sendResponse($activationDetails, 'user course details.');
    }

    public function prelogin(){
        $settings = Setting::get();
        $data = array();

        foreach($settings as $setting){
            $item = json_decode($setting->value);
            if($setting->setting_option == 'base_url'){
                $data['server']['url'] = $item->url;
            }if($setting->setting_option == 'app_maintainance'){
                $data['app_maintainance']['status'] = $item->maintain_mode;
                $data['app_maintainance']['message'] = $item->message;
            }if($setting->setting_option == 'app_version'){
                $data['app']['link'] = $item->link;
                $data['app']['version'] = $item->version;
            }if($setting->setting_option == 'pages'){
                $data['pages']['privacy_policy'] = 'privacy-policy';
                $data['pages']['term_condition'] = 'term-condition';
                $data['pages']['support'] = 'support';
            }if($setting->setting_option == 'announcements'){
                $data['announcements']['status'] = $item->status;
                $data['announcements']['heading'] = $item->heading;
                $data['announcements']['body'] = $item->body;
                $data['announcements']['image'] = 'storage/'.$item->image;
            }
        }

        return $this->sendResponse($data, 'Prelogin api data.');
    }



}
