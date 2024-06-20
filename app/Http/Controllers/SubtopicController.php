<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;

class SubtopicController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search input from the request
        $query = Subtopic::with('topic');

        if (!empty($search)) {
            $query->where('heading', 'like', '%' . $search . '%');
        }

        $subtopics = $query->paginate(10)->appends(['search' => $search]); // 10 chapters per page

        return view('subtopics.index', compact('subtopics', 'search'));
    }

    public function create()
    {
        $topics = Topic::all();
        return view('subtopics.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'heading' => 'required',
            'body' => 'required',
            'topic_id' => 'required|exists:topics,id',
            'primary_key' => 'required|string|max:250',
            'secondary_key' => 'required|string|max:250',
            'file_name' => 'required|string|max:250',
            'folder_name' => 'nullable|string|max:255',
        ]);

        Subtopic::create($validatedData);

        return redirect()->route('subtopics.index')
            ->with('success', 'SubTopic created successfully');
    }

    public function edit(Subtopic $subtopic)
    {
        $topics = Topic::all();
        return view('subtopics.edit', compact('subtopic'), compact('topics'));
    }

    public function update(Request $request, Subtopic $subtopic)
    {
        $validatedData = $request->validate([
            'heading' => 'required',
            'body' => 'required',
            'topic_id' => 'required|exists:topics,id',
            'primary_key' => 'required|string|max:250',
            'secondary_key' => 'required|string|max:250',
            'file_name' => 'required|string|max:250',
        ]);

        $subtopic->update($validatedData);

        return redirect()->route('subtopics.index')
            ->with('success', 'SubTopic updated successfully');
    }

    public function destroy(Subtopic $subtopic)
    {
        $subtopic->delete();

        return redirect()->route('subtopics.index')
            ->with('success', 'SubTopic deleted successfully');
    }

    public function show(){
        return view('subtopics.import');
    }

    public function import(Request $request){

        if($request->has('file')){

            $data= $this->ImportFile($request->file);
            if(count($data) > 0){
                foreach ($data as $column){
                    $standard = new Subtopic;
                    $standard->heading = $column["A"];
                    $standard->body = $column["B"];
                    $standard->topic_id = $column["C"];
                    $standard->primary_key = $column["D"];
                    $standard->secondary_key = $column["E"];
                    $standard->file_name = $column["F"];
                    $standard->folder_name = $column["G"];
                    $standard->save();
                }
                return redirect()->back()
                    ->with('success', 'File Imported successfully ..!');
            }else{
                return redirect()->back()
                    ->with('success', 'Corrupt Subject File Inputs.');
            }
        }else{
            return redirect()->back()->with("error","File not uploaded ..!");
        }
    }
}
