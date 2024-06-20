<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use App\Models\Subject;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Subject::select('subjects.id', 'subjects.name as subject_name', 'mediums.name as medium_name', 'boards.name as board_name', 'states.name as state_name','standards.name as standard_name')
                ->leftJoin('standards', 'subjects.standard_id', '=', 'standards.id')
                ->leftJoin('mediums', 'standards.medium_id', '=', 'mediums.id')
                ->leftJoin('boards', 'mediums.board_id', '=', 'boards.id')
                ->leftJoin('states', 'boards.state_id', '=', 'states.id');
            if ($request->has('search') && !empty($request->input('search')['value'])) {
                $searchTerm = $request->input('search')['value'];
                $data->where(function ($query) use ($searchTerm) {
                    $query->where('subjects.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('standards.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('mediums.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('boards.name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('states.name', 'like', '%' . $searchTerm . '%');
                });
            }
            /*  <a href="' . url('admin/courses/create', ['type' => 'subject', 'subject_id' => $row->id]). '" class="btn btn-sm btn-success" ><span class="mdi mdi-plus"></span> Add Course</a>*/
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex gap-1" >

                <a href="' . route('subjects.edit', ['subject' => $row->id]) . '" class="btn btn-sm btn-info">
                    <span class="mdi mdi-pen"></span> Edit
                </a>
                <form action="' . route('subjects.destroy', ['subject' => $row->id]) . '" method="POST" class="d-inline">
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
        return view('subjects.index');
    }

    public function create()
    {
        $standards = Standard::all();
        return view('subjects.create', compact('standards'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'folder_name' => 'required|string|max:255',
            'standard_id' => 'required|exists:standards,id',
        ]);

        Subject::create($validatedData);

        return redirect()->route('subjects.index')
            ->with('success', 'Subjects created successfully');
    }

    public function edit(Subject $subject)
    {
        $standards = Standard::all();
        return view('subjects.edit', compact('subject'), compact('standards'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'standard_id' => 'required|exists:standards,id',
        ]);

        $subject->update($validatedData);

        return redirect()->route('subjects.index')
            ->with('success', 'Subjects updated successfully');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully');
    }

    public function show(){
        return view('subjects.import');

    }
    public function subjectBulkUpload(Request $request){
        if($request->all('subjectFile')['subjectFile'] ){
            if($request->all('subjectFile')['subjectFile']){
                $file = $request->file('subjectFile');
                $spreadsheet = IOFactory::load($file);
                $worksheet = $spreadsheet->getActiveSheet();
                $columns = [];
                foreach ($worksheet->getRowIterator() as $row) {
                    $cellIterator = $row->getCellIterator();
                    $rowData = [];
                    foreach ($cellIterator as $cell) {
                        $rowData[] = $cell->getValue();
                    }
                    $columns[] = $rowData;
                }
                $result = array_slice($columns, 1);
                if(count($result) > 0){

                    foreach ($result as $column){
                        $subject = new Subject;
                        $subject->name = $column["A"];
                        $subject->standard_id = $column["B"];
                        $subject->folder_name = $column["C"];


                        $subject->save();
                    }
                }else{
                    return redirect()->route('subjects.index')
                    ->with('success', 'Corrupt Subject File Inputs.');
                }
            }

            return redirect('/admin/subjects/index')
            ->with('success', 'Data Imported Successfully.');

        }

        return redirect('/admin/subjects/index')
        ->with('success', 'Please Select the File.');
    }


    public function subjectAddCourses(){
        $subject = Subject::all();
        return view('subjects.add-courses',compact('subject'));
    }
}
