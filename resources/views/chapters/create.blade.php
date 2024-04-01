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
                <h4 class="page-title">Create Chapter</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/chapters">Chapter List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Chapter</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/chapters')}}" class="btn btn-danger text-white">Chapter List</a>
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
                        <h3 class="mb-3">Chapter Form</h3>
                        <form action="{{url('admin/chapters')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="subject">Select Subject</label>
                                <select name="subject_id" id="subject" class="form-control">
                                    @foreach ($subjects as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach 
                                </select>
                                @error('subject_id')
                                    <span class="text-danger">Please select subject</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Chapter Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                                @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="folder_name">Folder Name</label>
                                <input type="text" name="folder_name" class="form-control" id="folder_name">
                                @error('folder_name')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
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