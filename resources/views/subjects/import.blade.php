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
    #optioncontainer{
        padding: 10px;
        border: 2px dashed #cccccc70;
    }
    .content ul>li{
        font-size: 16px;
        line-height: 1.5;
        padding-top: 10px;
    }
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Import Subject</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/subjects">Subject</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Import Subjects</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/subjects')}}" class="btn btn-danger text-white">Subject List</a>
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
            <div class="col-sm-3"></div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                    </div>
                    <div class="px-4 pb-4">
                        <h3 class="mb-3">Add Subject Files</h3>
                        <form action="{{url('admin/subjects/bulk-upload')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group" id="correct_answere">
                                <label for="correct_ans">Add Subject File</label>
                                <input type="file" name="subjectFile" class="form-control" accept=".xls,.xlsx, .ods">
                                @error('subjectFile')
                                    <span class="text-danger">Please select subject file.</span>
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
