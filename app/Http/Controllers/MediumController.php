<?php

namespace App\Http\Controllers;

use App\Models\Medium;
use App\Models\Board;
use App\Traits\Import;
use Illuminate\Http\Request;

class MediumController extends Controller
{
    use Import;
    public function index()
    {
        $mediums = Medium::with('board')->get();
        return view('mediums.index', compact('mediums'));
    }

    public function create()
    {
        $boards = Board::all();
        return view('mediums.create', compact('boards'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'folder_name' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id',
        ]);

        Medium::create($validatedData);

        return redirect()->route('mediums.index')
            ->with('success', 'Medium created successfully');
    }

    public function edit(Medium $medium)
    {
        $boards = Board::all();
        return view('mediums.edit', compact('medium'), compact('boards'));
    }

    public function update(Request $request, Medium $medium)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'board_id' => 'required|exists:boards,id',
        ]);

        $medium->update($validatedData);

        return redirect()->route('mediums.index')
            ->with('success', 'Medium updated successfully');
    }

    public function destroy(Medium $medium)
    {
        $medium->delete();

        return redirect()->route('mediums.index')
            ->with('success', 'Medium deleted successfully');
    }

    public function show(){
        return view('mediums.import');
    }
    public function import(Request $request){

        if($request->has('file')){

            $data= $this->ImportFile($request->file);
            if(count($data) > 0){
                foreach ($data as $column){
                    $standard = new Medium;
                    $standard->name = $column["A"];
                    $standard->board_id = $column["B"];
                    $standard->folder_name = $column["C"];
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

    public function sample_download($name){
        return $this->generateSampleFile($name);

    }
}
