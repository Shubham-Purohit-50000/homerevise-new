<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Activation;
use App\Models\AppUsage;
use App\Models\PlayedTopics;
use App\Models\QuizAnalytics;
use Illuminate\Http\Request;
use Hash;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException; // Import QueryException class

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'activation_key' => 'required|exists:activations,activation_key',
        ]);

        $data['password'] =  Hash::make($request->password);

        $user = User::create($data);
        $activation = Activation::where('activation_key', $request->activation_key)->first();
        if($activation->user_id == null){
            $activation->user_id = $user->id;
            $activation->activation_time = Carbon::now();
            $activation->expiry_date = Carbon::now();
            // $activation->save();
        }else{
            return redirect()->route('users.index')->with('error', 'This activation key is already in use, please asign a new key to this user');
        }


        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'email' => [
                'nullable','email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the allowed file types and maximum size as needed.
        ]);

        $data = $request->all();

        // Handle profile picture upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profile_pictures', 'public');
            // Save the image path in the user's database record
            $data['image'] = $imagePath;
        }
        if($request->has('password') and $request->password != null and $request->password != ''){
            $data['password'] = Hash::make($data['password']);
        }else{
            $data['password'] = $user->password;
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function addKey(User $user){
        return view('users.addCourse', compact('user'));
    }

    public function submitKey(Request $request, User $user){
        $data = $request->validate([
            'activation_key' => 'required|exists:activations,activation_key',
        ]);

        $activation = Activation::where('activation_key', $request->activation_key)->first(); 
        if($activation->user_id == null){
            $activation->user_id = $user->id;
            $activation->activation_time = Carbon::now();
            $activation->expiry_date = $activation->course->device_type == "mobile" ? Carbon::now()->addMonths($activation->course->duration) : NULL ;
            $activation->expiry_count = $activation->course->device_type == "android_box" ? $activation->course->access_count : NULL ;

            $activation->save();
        }else{
            return redirect()->route('users.index')->with('error', 'This activation key is already in use');
        }

        return redirect()->route('users.index')->with('success', 'Course added successfully');
    }

    public function show(User $user){
        $activations = $user->activation; 
        $appUsage = AppUsage::where('user_id','=',$user->id)->first();
        $playedTopics = PlayedTopics::where('user_id','=',$user->id)->get();

        $time = '00:00';
        if($appUsage){            
            $minutes = gmdate("i", $appUsage->app_usage_time);
            $seconds = gmdate("s", $appUsage->app_usage_time);
            $time = $minutes.':'.$seconds;
        }
        $quizAnalytics = QuizAnalytics::where('user_id','=',$user->id)->get();

        return view('users.show', compact('user','activations','time','playedTopics','quizAnalytics'));
    }

    public function updateCourseDuration(Request $request, Activation $activation){

        if($request->count>0){
            // $course = Course::where(['id'=>$activation->course_id])->first();
            // $course->update(["access_count"=>$request->count]);
            try {
                // $activation->update([
                //     'expiry_count' => $request->count
                // ]);
                $activation->expiry_count = $request->count;
                $activation->update();
            } catch (QueryException $e) {
                // Handle the exception
                // For example, you can log the error or return a response to the user
                dd($e->getMessage());
            }
            return redirect()->back()->with('success', 'Data updated successfully ..!');
        }
        $expiry_date = Carbon::parse($request->expiry_date);
        $expiry_date = $expiry_date->format('Y-m-d H:i:s');

        $activation->update([
            'expiry_date' => $expiry_date
        ]);
        return redirect()->back()->with('success', 'Expiry Date updated successfully');
    }

    public function deRegisterDevice(User $user){
        $user->update(['device_id' => null]);
        return redirect()->back()->with('success', 'This user device_id removed');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

}
