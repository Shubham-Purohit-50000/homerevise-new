<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Topic;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Topic::select('topics.id','topics.heading', 'chapters.name as chapter_name', 'subjects.name as subject_name', 'mediums.name as medium_name', 'boards.name as board_name', 'states.name as state_name','standards.name as standard_name')
            ->leftJoin('chapters', 'topics.chapter_id', '=', 'chapters.id')
                ->leftJoin('subjects', 'chapters.subject_id', '=', 'subjects.id')
                ->leftJoin('standards', 'subjects.standard_id', '=', 'standards.id')
                ->leftJoin('mediums', 'standards.medium_id', '=', 'mediums.id')
                ->leftJoin('boards', 'mediums.board_id', '=', 'boards.id')
                ->leftJoin('states', 'boards.state_id', '=', 'states.id');

            if ($request->has('search') && !empty($request->input('search')['value'])) {
                $searchTerm = $request->input('search')['value'];
                $data->where(function ($query) use ($searchTerm) {
                    $query->where('heading', 'like', '%' . $searchTerm . '%')
                        ->orWhere('chapters.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('subjects.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('standards.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('mediums.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('boards.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('states.name', 'like', '%' . $searchTerm . '%');
                });
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex gap-1" >
                <a href="' . route('topics.edit', ['topic' => $row->id]) . '" class="btn btn-sm btn-info">
                    <span class="mdi mdi-pen"></span> Edit
                </a>
                <form action="' . route('topics.destroy', ['topic' => $row->id]) . '" method="POST" class="d-inline">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-sm btn-danger text-white" onclick="return confirmDelete(\'On delete this record, All data such as subtopic, courses and activation_keys etc under this record will be deleted\')">
                        <span class="mdi mdi-delete-empty"></span> Delete
                    </button>
                </form>
            </div>';

                })

                ->rawColumns(['action'])
                ->make(true);

        }
        return view('topics.index');
    }


    public function create()
    {
        $chapters = Chapter::all();
        return view('topics.create', compact('chapters'));
    }

    public function store(Request $request)
    {
        if($request->file('add_file')){
            $file = $request->file('add_file');
            $path = $file->store('uploads/file', 'public');

            $url = asset('storage/' . $path);

            $request->merge(['fileUrl' => $url]);
        }
        $validatedData = $request->validate([
            'heading' => 'required',
            'body' => 'required',
            'chapter_id' => 'required|exists:chapters,id',
            'primary_key' => 'required|string|max:250',
            'secondary_key' => 'required|string|max:250',
            'file_name' => 'required|string|max:250',
            'folder_name' => 'nullable|string|max:255',
            'fileUrl' => 'nullable|string|max:255',
        ]);

        Topic::create($validatedData);

        return redirect()->route('topics.index')
            ->with('success', 'Topic created successfully');
    }

    public function edit(Topic $topic)
    {
        $chapters = Chapter::all();
        return view('topics.edit', compact('topic'), compact('chapters'));
    }

    public function update(Request $request, Topic $topic)
    {
        if($request->file('add_file')){
            $file = $request->file('add_file');
            $path = $file->store('uploads/file', 'public');

            $url = asset('storage/' . $path);

            $request->merge(['fileUrl' => $url]);
        }
        $validatedData = $request->validate([
            'heading' => 'required',
            'body' => 'required',
            'chapter_id' => 'required|exists:chapters,id',
            'primary_key' => 'required|string|max:250',
            'secondary_key' => 'required|string|max:250',
            'file_name' => 'required|string|max:250',
            'fileUrl' => 'nullable|string|max:255',
        ]);

        $topic->update($validatedData);

        return redirect()->route('topics.index')
            ->with('success', 'Topic updated successfully');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('topics.index')
            ->with('success', 'Topic deleted successfully');
    }

    public function show(){
        return view('topics.import');
    }

    public function import(Request $request){

        if($request->has('file')){

            $data= $this->ImportFile($request->file);
            if(count($data) > 0){
                foreach ($data as $column){
                    $standard = new Topic;
                    $standard->heading = $column["A"];
                    $standard->body = $column["B"];
                    $standard->chapter_id = $column["C"];
                    $standard->primary_key = $column["D"];
                    $standard->secondary_key = $column["E"];
                    $standard->file_name = $column["F"];
                    $standard->folder_name = $column["G"];
                    $standard->fileUrl = $column["H"];
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
