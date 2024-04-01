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
                <h4 class="page-title">Create Topic</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/subtopics">Topic</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Topic</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/topics')}}" class="btn btn-danger text-white">Topic List</a>
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
                        <h3 class="mb-3">Topic Form</h3>
                        <h6 class="mb-3">Folder Name is : {{$subtopic->folder_name}}</h6>
                        <form action="{{route('subtopics.update', ['subtopic'=>$subtopic->id])}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="topic">Select Topic</label>
                                <select name="topic_id" id="topic" class="form-control">
                                    @foreach ($topics as $item)
                                    <option value="{{$item->id}}">{{$item->heading}}</option>
                                    @endforeach 
                                </select>
                                @error('topic')
                                    <span class="text-danger">Please select Topic</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Sub-Topic Heading</label>
                                <input type="text" name="heading" class="form-control" id="name" placeholder="Enter Heading" value="{{$subtopic->heading}}">
                                @error('heading')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Topic Body</label>
                                <textarea name="body" class="form-control" id="body" cols="30" rows="10" placeholder="Enter Topic Body">{{$subtopic->body}}</textarea>
                                @error('body')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="primary_key">Primary Key</label>
                                <input type="text" name="primary_key" class="form-control" id="primary_key" value="{{$subtopic->primary_key}}">
                                @error('primary_key')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="secondary_key">Secondary key</label>
                                <input type="text" name="secondary_key" class="form-control" id="secondary_key" value="{{$subtopic->secondary_key}}">
                                @error('secondary_key')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="file_name">File Name</label>
                                <input type="text" name="file_name" class="form-control" id="file_name" value="{{$subtopic->file_name}}">
                                @error('file_name')
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