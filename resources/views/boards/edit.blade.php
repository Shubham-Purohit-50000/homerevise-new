@extends('backend.layout')
@section('title','Dashbord')
@section('content')

<style>
    .left-border{
        border-left: 3px solid;
    }
    .info_card h3{
        font-size: 1.2rem;
    }
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Edit Board</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/boards">Board List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit State</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/boards')}}" class="btn btn-danger text-white">Board List</a>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">

        <div class="row">
            <!-- column -->
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
        
                    </div>
                    <div class="px-4 pb-4">
                        <h3 class="mb-3">Board Form</h3>
                        <h6 class="mb-3">Folder Name is : {{$board->folder_name}}</h6>
                        <form action="{{route('boards.update', ['board'=>$board->id])}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="state">Select State</label>
                                <select name="state_id" id="state" class="form-control">
                                    @foreach ($states as $item)
                                    <option value="{{$item->id}}"  {{($board->state_id == $item->id) ? "selected" : ""}}>{{$item->name}}</option>
                                    @endforeach 
                                </select>
                                @error('state_id')
                                    <span class="text-danger">Please select State</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Board Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="{{$board->name}}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn btn-success text-white">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>
@endsection
