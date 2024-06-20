<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Standard;
use App\Models\Activation;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('id','DESC')->get();
        return view('courses.index', compact('courses'));
    }

//    public function create($type, $id)
     public function create()
    {
//        $data['type'] = $type;
 //       $data['id'] = $id;

        // You can load data for dropdowns like standards and subjects here
   //     return view('courses.create', compact('data'));

        $subjects = Subject::all();
        $standards = Standard::all();
        return view('courses.create',compact('subjects','standards'));
    }

    public function viewCourse($id){
        $courses = Course::findOrFail($id);
        $coursesByName = Course::where('name','=',$courses->name)->get();

        return view('courses.view', compact('coursesByName'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'folder_name' => 'nullable|string|max:255',
            'duration' => 'required|numeric',
            'count' => 'required|numeric',
            'status' => 'required|numeric',
        ]);
       // $activationKey = 'HR'.generateRandomString(6);


                if($request->course_type == "standard"){

                $course = new Course;
                $course->name = $request->name;
                $course->standard_id =$request->standard_id;
                $course->duration =$request->duration;
                $course->status =$request->status;
                $course->device_type =$request->device_type;
                $course->folder_name =$request->folder_name;
                $course->access_count =$request->access_count;
                $course->save();
                    $activationKey = 'HR'.generateRandomString(6);

                    for($i = 0; $i < $request->count -1; $i++){
                        // Check if the generated key already exists in the activations table
                        while (Activation::where('activation_key', $activationKey)->exists()) {
                            // If it exists, generate a new key and check again
                            $activationKey = 'HR'.generateRandomString(6);
                        }

                        Activation::create([
                            'course_id' => $course->id,
                            'activation_key' => $activationKey,
                        ]);
                    }

                Activation::create([
                    'course_id' => $course->id,
                    'activation_key' => $activationKey,
                ]);
        }elseif($request->course_type == "subject"){

                $course = new Course;
                $course->name = $request->name;
                $course->subject_id =$request->subjects_id;
                $course->duration =$request->duration;
                $course->status =$request->status;
                $course->device_type =$request->device_type;
                $course->folder_name =$request->folder_name;
                $course->access_count =$request->access_count;
                $course->save();
                $activationKey = 'HR'.generateRandomString(6);

                for($i = 0; $i < $request->count -1; $i++){
                    // Check if the generated key already exists in the activations table
                    while (Activation::where('activation_key', $activationKey)->exists()) {
                        // If it exists, generate a new key and check again
                        $activationKey = 'HR'.generateRandomString(6);
                    }

                    Activation::create([
                        'course_id' => $course->id,
                        'activation_key' => $activationKey,
                    ]);
                }

                Activation::create([
                    'course_id' => $course->id,
                    'activation_key' => $activationKey,
                ]);
        }


        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully.');
    }


    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        // You can load data for dropdowns like standards and subjects here
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|numeric',
            'status' => 'required|numeric',
        ]);
        $course->standard_id  = $course->standard_id ? $course->standard_id : Standard::findOrFail(Subject::findOrFail($course->subject_id)->standard_id)->id;

        $course->update($validatedData);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function showActivation(Course $course){
        return view('courses.show', compact('course'));
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    function deleteActivation($id){
        $activation = Activation::findOrFail($id);

        $activation->delete();
        return redirect()->back()
            ->with('success', 'Key deleted successfully.');
    }

}
