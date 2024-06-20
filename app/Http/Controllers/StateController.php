<?php
// app/Http/Controllers/StateController.php
namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        $states = State::all();
        return view('states.index', compact('states'));
    }

    public function create()
    {
        return view('states.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'folder_name' => 'required|string|max:255',
        ]);

        State::create($request->all());

        return redirect()->route('states.index')
            ->with('success', 'State created successfully.');
    }

    public function show(State $state)
    {
        return view('states.show', compact('state'));
    }

    public function edit(State $state)
    {
        return view('states.edit', compact('state'));
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $state->update($request->all());

        return redirect()->route('states.index')
            ->with('success', 'State updated successfully.');
    }

    public function destroy(State $state)
    {
        $state->delete();

        return redirect()->route('states.index')
            ->with('success', 'State deleted successfully.');
    }

    public function show_import(){
        return view('states.import');
    }
    public function import(Request $request){

        if($request->has('file')){

            $data= $this->ImportFile($request->file);
            if(count($data) > 0){
                foreach ($data as $column){
                    $standard = new State;
                    $standard->name = $column["A"];
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
