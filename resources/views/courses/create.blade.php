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
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/courses">Course List</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Course</li>
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
                            {{--<input type="hidden" name="type" value="{{$data['type']}}">
                            <input type="hidden" name="id" value="{{$data['id']}}">--}}
                            <div class="form-group">
                                <label for="name">Course Name</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="count">Course Type</label>
                                <select name="course_type" class="form-control" id="course_type">
                                    <option value="" disabled selected>Select Course Type</option>
                                    <option value="standard">Course for Standard </option>
                                    <option value="subject">Course for Subject</option>
                                </select>
                                @error('count')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group" id="subjects-options" >
                                <label for="subjects">Select Subjects</label>
                                <select id="subjects_id" class="form-control js-example-basic-multiple " name="subjects_id[]" multiple="multiple"  >
                                    @foreach ($subjects as $item)
                                        <option value="{{$item->id}}"> {{$item->standard->medium->name}} | {{$item->standard->name}} | {{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="standard-options" >
                                <label for="standard">Select Standard</label>
                                <select id="standard_id" class="form-control js-example-basic-multiple " name="standard_id[]" multiple="multiple"  >
                                    @foreach ($standards as $item)
                                        <option value="{{$item->id}}"> {{$item->medium->name}} | {{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="count">Number of Activation keys</label>
                                <input type="number" name="count" class="form-control" id="count" min="1" value="1">
                                @error('count')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="device_type">Device Type</label>
                                <select name="device_type" id="device_type" name="device_type" class="form-control">
                                    <option value="" selected>Select Device Type</option>
                                    <option value="mobile" >Mobile</option>
                                    <option value="android_box">Android Box</option>
                                </select>
                            </div>
                            <div class="form-group" id="courseDuration">
                                <label for="duration">Course Duration (months)</label>
                                <input type="number" name="duration" class="form-control" id="duration" min="0" value="0">
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

                $("#courseDuration").hide();
                $("#duration").val(0);
                if (!$('input[name="access_count"]').length) {
                    var countInput = $('<input>').attr({
                        type: 'number',
                        name: 'access_count',
                        placeholder: 'Enter count',
                        class: 'form-control',
                        min: '10',
                        value: '10',
                        required: true,
                    });
                    var countLabel = $('<label>').text('Course Duration (Login Attempt)').attr({
                        for: 'access_count',
                        class: 'mt-3 access-class',
                    });

                    $(this).parent().append(countLabel).append(countInput);
                }
            } else {
                $('input[name="access_count"]').remove();
                $('.access-class').remove();
                $("#courseDuration").show();
            }
        });
        $('.js-example-basic-multiple').select2();
        $("#subjects-options").hide();
        $("#standard-options").hide();
        $("#courseDuration").hide();
        $("#course_type").on("change", function(){
            var course_type = $("#course_type").val();
            if(course_type == "subject"){
                $("#subjects-options").show();
                $("#standard-options").hide();
                $("#subjects_id").attr('required', 'required');
                $("#standard_id").attr('required', false);

            }else{
                $("#subjects-options").hide();
                $("#standard-options").show();
                $("#standard_id").attr('required', 'required');
                $("#subjects_id").attr('required', false);
            }
        });

    });
</script>
@endsection
