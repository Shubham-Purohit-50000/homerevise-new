<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\State;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    // app/Http/Controllers/BoardController.php

    public function index()
    {
        $boards = Board::with('state')->get();
        return view('boards.index', compact('boards'));
    }

    public function create()
    {
        $states = State::all();
        return view('boards.create', compact('states'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'folder_name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id',
        ]);

        Board::create($validatedData);

        return redirect()->route('boards.index')
        ->with('success', 'Board created successfully.');
    }

    public function edit(Board $board)
    {
        $states = State::all();
        return view('boards.edit', compact('board'), compact('states'));
    }

    public function update(Request $request, Board $board)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'state_id' => 'required|exists:states,id',
        ]);

        $board->update($validatedData);

        return redirect()->route('boards.index')
        ->with('success', 'Board Updated successfully.');
    }

    public function destroy(Board $board)
    {
        $board->delete();
        return redirect()->route('boards.index');
    }

    public function show(){
        return view('boards.import');
    }
    public function import(Request $request){

        if($request->has('file')){

            $data= $this->ImportFile($request->file);
            if(count($data) > 0){
                foreach ($data as $column){
                    $standard = new board;
                    $standard->name = $column["A"];
                    $standard->state_id = $column["B"];
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

}
