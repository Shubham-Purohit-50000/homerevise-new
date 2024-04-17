<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use Hash;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException; 

class StaffController extends Controller
{
    public function index(Request $request){ 
        $staff = Staff::latest()->get();
        return view('staff.index', compact('staff'));
    }

    public function create(Request $request){    
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $data['password'] =  Hash::make($request->password); 
        $staff = new Staff;
        $staff->name = $request->name;
        $staff->phone = $request->phone;
        $staff->email = $request->email;
        $staff->isVerified = $request->status == "1" ? 1 : 0;
        $staff->password = Hash::make($request->password);
        
        if(!$staff->save()){
            return redirect()->route('staff.index')->with('error', 'Something went wrong.');
        }else{
            return redirect()->route('staff.index')->with('success', 'Staff created successfully');
        } 
    }

    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'email' => [
                'nullable','email',
                Rule::unique('staff', 'email')->ignore($staff->id),
            ],
            'phone' => [
                'nullable',
                Rule::unique('staff', 'phone')->ignore($staff->id),
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
            $data['password'] = $staff->password;
        }

        $staff->name = $data['name'];
        $staff->phone = $data['phone'];
        $staff->email = $data['email'];
        $staff->isVerified = $data['status'] == "1" ? 1 : 0;
        $staff->password = $data['password'];
        $staff->address = $data['address'];
        $staff->update();
        return redirect()->route('staff.index')->with('success', 'User updated successfully');
    }

    public function show(Staff $staff)
    {
        return view('staff.show', compact('staff'));
    }
}
