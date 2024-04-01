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
                <h4 class="page-title">Course Topic</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Course</a></li>
                            <li class="breadcrumb-item active" aria-current="page">course-topic</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/courses')}}" class="btn btn-danger text-white">Course List</a>
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
                        <h3 class="mb-3">Course Form</h3>
                        <form action="{{ url('admin/courses') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="{{$data['type']}}">
                            <input type="hidden" name="id" value="{{$data['id']}}">
                            <div class="form-group">
                                <label for="name">Course Name</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="count">Number of Activation keys</label>
                                <input type="number" name="count" class="form-control" id="count" min="1" value="1">
                                @error('count')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="duration">Course Duration (months)</label>
                                <input type="number" name="duration" class="form-control" id="duration" min="1" value="1">
                                @error('duration')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="status">Course Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                @error('status')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="folder_name">Folder Name (optional)</label>
                                <input type="text" name="folder_name" class="form-control" id="folder_name">
                                @error('folder_name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="device_type">Device Type</label>
                                <select name="device_type" id="device_type" class="form-control">
                                    <option value="mobile" selected>Mobile</option>
                                    <option value="android_box">Android Box</option>
                                </select>
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
<script>
    $(document).ready(function() {
        $('form select[name="device_type"]').change(function() {
            if ($(this).val() === "android_box") {
                if (!$('input[name="access_count"]').length) {
                    var countInput = $('<input>').attr({
                        type: 'number',
                        name: 'access_count',
                        placeholder: 'Enter count',
                        class: 'form-control mt-3',
                        min: '10',
                        value: '10',
                        required: true,
                    });
                    $(this).parent().append(countInput);
                }
            } else {
                $('input[name="access_count"]').remove();
            }
        });
    });
</script>
@endsection