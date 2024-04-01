<?php

namespace App\Http\Controllers;

use App\Models\Medium;
use App\Models\Standard;
use App\Traits\Import;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
class StandardController extends Controller
{
    use Import;
    public function index()
    {
        $standards = Standard::with('medium')->get();
        return view('standards.index', compact('standards'));
    }

    public function create()
    {
        $mediums = Medium::all();
        return view('standards.create', compact('mediums'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'folder_name' => 'required|string|max:255',
            'medium_id' => 'required|exists:mediums,id',
        ]);

        Standard::create($validatedData);

        return redirect()->route('standards.index')
            ->with('success', 'Standard created successfully');
    }

    public function edit(Standard $standard)
    {
        $mediums = Medium::all();
        return view('standards.edit', compact('standard'), compact('mediums'));
    }

    public function update(Request $request, Standard $standard)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'medium_id' => 'required|exists:mediums,id',
        ]);

        $standard->update($validatedData);

        return redirect()->route('standards.index')
            ->with('success', 'Standard updated successfully');
    }

    public function destroy(Medium $medium)
    {
        $medium->delete();

        return redirect()->route('standards.index')
            ->with('success', 'Standard deleted successfully');
    }

    public function show(){
            return view('standards.import');
    }
    public function importStandard(Request $request){

        if($request->has('standard_file')){

           $data= $this->ImportFile($request->standard_file);
            if(count($data) > 0){
                foreach ($data as $column){
                    $standard = new Standard;
                    $standard->name = $column["A"];
                    $standard->medium_id = $column["B"];
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

    public function sample_download(){
        $name= "standards";
     return $this->generateSampleFile($name);

    }
}
