<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ChartController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Setting;
use App\Models\Banner;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Validator;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends Controller
{

    public function login(){

        if(Auth::guard('admin')->check()){

            return redirect('/admin/dashboard');
        }
        return view('backend.login');
    }

    public function postLogin(Request $request){

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended('admin/dashboard')
                        ->withSuccess('You have Successfully loggedin');
        }

        return redirect("/")->with('error','Oppes! You have entered invalid credentials');
    }

    public function dashboard(){
        return view('backend.dashboard');
    }


    public function setting(){
        $settings = Setting::get();
        return view('backend.setting', compact('settings'));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return view('backend.login'); // You can redirect the user to any URL after logout.
    }

    public function maintainanceMode(Request $request){

        $request->validate([
            'maintain_mode' => 'required',
            'message' => 'required|string|max:250',
        ]);

        $app_maintainance = Setting::where('setting_option', 'app_maintainance')->first();
        $app_maintainance->value = $request->all();
        $app_maintainance->update();

        return redirect()->back()
            ->with('success', 'setting updated successfully.');
    }

    public function appVerion(Request $request){

        $request->validate([
            'link' => 'required|string|max:250',
            'version' => 'required|string|max:250',
        ]);

        $setting = Setting::where('setting_option', 'app_version')->first();
        $setting->value = $request->all();
        $setting->update();

        return redirect()->back()
            ->with('success', 'setting updated successfully.');
    }

    public function pages(Request $request){
        $request->validate([
            'privacy_policy' => 'required',
            'term_condition' => 'required',
            'Support' => 'required',
        ]);

        $setting = Setting::where('setting_option', 'pages')->first();
        $setting->value = $request->all();
        $setting->update();

        return redirect()->back()
            ->with('success', 'setting updated successfully.');
    }

    public function announcement(Request $request){

        $request->validate([
            'status' => 'required|string',
            'heading' => 'required|string',
            'body' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $input = $request->all();
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcement_pictures', 'public');
            // Save the image path in the user's database record
            $input['image'] = $imagePath;
        }

        $setting = Setting::where('setting_option', 'announcements')->first();
        $setting->value = json_encode($input);
        $setting->update();

        return redirect()->back()
            ->with('success', 'setting updated successfully.');
    }

    public function base_url(Request $request){

        $request->validate([
            'url' => 'required',
        ]);

        $setting = Setting::where('setting_option', 'base_url')->first();
        $setting->value = $request->all();
        $setting->update();

        return redirect()->back()
            ->with('success', 'setting updated successfully.');
    }

    public function updateApk(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required', // Adjust the file size limit as needed (in KB)
        ]);

        // Check if a file was uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Store the file with the desired name
            $fileName = 'homerevise.apk';
            $file->storeAs('uploads/apk', $fileName, 'public'); // 'uploads' is the storage folder name

            // You can also store the file path in the database if needed
            // For example, if you have a 'files' table with a 'path' column:
            // File::create(['path' => 'uploads/' . $fileName]);

            // Optionally, you can return a success response
            return response()->json(['message' => 'File uploaded successfully']);
        }

        // Handle the case where no file was uploaded
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function updateWinApk(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required', // Adjust the file size limit as needed (in KB)
        ]);

        // Check if a file was uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Store the file with the desired name
            $fileName = 'homerevise_win.apk';
            $file->storeAs('uploads/apk', $fileName, 'public'); // 'uploads' is the storage folder name

            // You can also store the file path in the database if needed
            // For example, if you have a 'files' table with a 'path' column:
            // File::create(['path' => 'uploads/' . $fileName]);

            // Optionally, you can return a success response
            return response()->json(['message' => 'File uploaded successfully']);
        }

        // Handle the case where no file was uploaded
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function updateTvApk(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required', // Adjust the file size limit as needed (in KB)
        ]);

        // Check if a file was uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Store the file with the desired name
            $fileName = 'homerevise_tv.apk';
            $file->storeAs('uploads/apk', $fileName, 'public'); // 'uploads' is the storage folder name

            // You can also store the file path in the database if needed
            // For example, if you have a 'files' table with a 'path' column:
            // File::create(['path' => 'uploads/' . $fileName]);

            // Optionally, you can return a success response
            return response()->json(['message' => 'File uploaded successfully']);
        }

        // Handle the case where no file was uploaded
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function tools(){

        return view('backend.tools');
    }

    public function uploadImage(Request $request){
        // Validate the uploaded file
        $request->validate([
            'file' => 'required',
            'name' => 'required',
        ]);

        $fileName = $request->name;
        $fileParts = explode('.', $fileName);        
        $ext = ["jpg","jpeg","png"]; 
        if(!(count($fileParts) > 1)){
            return response()->json(['message' => 'File extension is missing.','errors' => true]);
        }
        $fileExt = $fileParts[1];
        if (!in_array($fileExt, $ext)) { 
            return response()->json(['message' => 'Invalid File extension.','errors' => true]);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');  
            $filePath = 'uploads/other/images/' . $fileName;
            $file->storeAs('uploads/other/images/', $fileName, 'public');

            $imageUrl = asset('storage/' . $filePath);

            return response()->json(['message' => 'File uploaded successfully','data' => $imageUrl]);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }


    public function banner()
    {
        $banners = Banner::latest()->get();
        return view('backend.banner', compact('banners'));
    }

    public function create_banner(){
        return view('backend.create-banner');
    }

    public function post_banner(Request $request)
    {
        $rules = [
            'sponsor' => 'required|string|max:100',
            'data' => 'nullable|string|max:500',
            'image' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ];

        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            // Store the file and get the path
            $filePath = $request->file('image')->store('sponsor/image', 'public');

            // Create a new banner with the validated data and file path
            Banner::create([
                'sponsor' => $request->sponsor,
                'data' => $request->data,
                'image' => $filePath,
            ]);

            return redirect('admin/sponsor')->with('success', 'Banner Added Successfully');
        } else {
            return back()->withErrors(['error' => 'Bad Request']);
        }
    }

    public function delete_banner($id)
    {
        // Find the banner by ID
        $banner = Banner::findOrFail($id);

        // Delete the image file from storage
        Storage::disk('public')->delete($banner->image);

        // Delete the banner record from the database
        $banner->delete();

        // Redirect with a success message
        return back()->with('success', 'Banner Deleted Successfully');
    }

    public function updateSponsorApi(Request $request){
        
        $request->validate([
            'sponsor_api' => 'required|in:active,deactive',
        ]);

        $sponsor = Setting::where('setting_option', 'sponsor')->first();
        $sponsor->value = $request->sponsor_api;
        $sponsor->update();

        return redirect()->back()
            ->with('success', 'setting updated successfully.');
    }


    public function downloadReport($id)
    {
        $user = User::findOrFail($id);

        $json = json_decode($user->database)->json;
        $data = json_decode($json, true);

        $spreadsheet = new Spreadsheet();

        // Populate played_topics sheet
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Played Topics');

        $headers = ['ID', 'Subject', 'Chapter', 'Topic', 'Duration (minutes)', 'Total Topics', 'Date'];
        $sheet->fromArray($headers, NULL, 'A1');

        // Set header font style to bold
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
        ];
        $sheet->getStyle('A1:G1')->applyFromArray($styleArray);

        $row = 2;
        if (isset($data['played_topics']) && is_array($data['played_topics'])) {
            foreach ($data['played_topics'] as $played_topic) {
                $sheet->setCellValue('A' . $row, $played_topic['id'] ?? '');
                $sheet->setCellValue('B' . $row, $played_topic['subject'] ?? '');
                $sheet->setCellValue('C' . $row, $played_topic['chapter'] ?? '');
                $sheet->setCellValue('D' . $row, $played_topic['topic'] ?? '');
                $sheet->setCellValue('E' . $row, $played_topic['duration_minutes'] ?? '');
                $sheet->setCellValue('F' . $row, $played_topic['total_topics'] ?? '');
                $sheet->setCellValue('G' . $row, isset($played_topic['date']) ? date('Y-m-d H:i:s', $played_topic['date'] / 1000) : '');
                $row++;
            }
        }

        // Create quiz_analytics sheet
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(1);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Quiz Analytics');

        $headers = ['ID', 'Quiz Name', 'Total Questions', 'Questions Attempted', 'Marks Earned', 'Total Marks', 'Right Questions', 'Wrong Questions', 'Date'];
        $sheet->fromArray($headers, NULL, 'A1');

        $sheet->getStyle('A1:I1')->applyFromArray($styleArray);

        $row = 2;
        if (isset($data['quiz_analytics']) && is_array($data['quiz_analytics'])) {
            foreach ($data['quiz_analytics'] as $quiz_analytic) {
                $sheet->setCellValue('A' . $row, $quiz_analytic['id'] ?? '');
                $sheet->setCellValue('B' . $row, $quiz_analytic['quiz_name'] ?? '');
                $sheet->setCellValue('C' . $row, $quiz_analytic['total_questions'] ?? '');
                $sheet->setCellValue('D' . $row, $quiz_analytic['questions_attempted'] ?? '');
                $sheet->setCellValue('E' . $row, $quiz_analytic['marks_earned'] ?? '');
                $sheet->setCellValue('F' . $row, $quiz_analytic['total_marks'] ?? '');
                $sheet->setCellValue('G' . $row, $quiz_analytic['right_questions'] ?? '');
                $sheet->setCellValue('H' . $row, $quiz_analytic['wrong_questions'] ?? '');
                $sheet->setCellValue('I' . $row, isset($quiz_analytic['date']) ? date('Y-m-d H:i:s', $quiz_analytic['date'] / 1000) : '');
                $row++;
            }
        }

        // Create daily_time sheet
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex(2);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daily Time');

        $headers = ['Subject', 'Topic', 'Chapter', 'Date', 'Duration (minutes)'];
        $sheet->fromArray($headers, NULL, 'A1');

        $sheet->getStyle('A1:F1')->applyFromArray($styleArray);

        $row = 2;
        if (isset($data['daily_time']) && is_array($data['daily_time'])) {
            foreach ($data['daily_time'] as $daily_time) {
                $sheet->setCellValue('A' . $row, $daily_time['subject'] ?? '');
                $sheet->setCellValue('B' . $row, $daily_time['topic'] ?? '');
                $sheet->setCellValue('C' . $row, $daily_time['chapter'] ?? '');
                $sheet->setCellValue('D' . $row, isset($daily_time['date']) ? date('Y-m-d H:i:s', $daily_time['date'] / 1000) : '');
                $sheet->setCellValue('E' . $row, $daily_time['duration_minutes'] ?? '');
                $row++;
            }
        }

        // Set the first sheet as the active sheet
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Analytics_report.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }


   }
