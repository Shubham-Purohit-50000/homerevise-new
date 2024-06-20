@extends('backend.layout')
@section('title','Quiz')
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
    .form-switch .form-check-input {
        width: 3.5em!important;        
        height: 1.5em!important;
        background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e);
        background-position: left center;
        border-radius: 2em;
    }
    .select2{
        width: 100%!important;
    }
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Edit Quiz</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/quizes">Quiz</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Quiz</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/quizes')}}" class="btn btn-danger text-white">Quiz List</a>
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
                        <div class="row ">
                            <div class="col-sm-6">
                                <h3 class="mb-3">Edit Quiz Details Form</h3>
                            </div>
                            <div class="col-sm-6 text-center">
                                <a href="{{ route('quizes.add-questions', ['id' => $quiz->id]) }}" class="btn btn-danger text-white">View Questions</a>
                            </div>
                        </div>
                        <form action="{{route('quizes.update', ['quize'=>$quiz->id])}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group">
                                    @error('quiz_standard')
                                        <p class="text-danger">Please select Standard </p>
                                    @enderror    
                                    @error('quiz_subject')
                                        <p class="text-danger">Please select Subject </p>
                                    @enderror    
                                    @error('quiz_chapter')
                                        <p class="text-danger">Please select Chapter </p>
                                    @enderror    

                                    @error('courses')
                                        <p class="text-danger">Please select Courses </p>
                                    @enderror
                                    <label for="quiz_title">Quiz Title</label>
                                    <input type="text" name="quiz_title" value="{{$quiz->title}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="medium">Select Quiz Category</label>
                                <select name="quiz_type" id="quiz_type" class="form-control" required>
                                    <option value="" disabled selected>Select Quiz Category</option>
                                    <option value="STWQ" {{$quiz->type == "STWQ" ? "selected" : ""}}>Standard Wise Quiz</option>
                                    <option value="SWQ" {{$quiz->type == "SWQ" ? "selected" : ""}}>Subject Wise Quiz</option>
                                    <option value="CWQ" {{$quiz->type == "CWQ" ? "selected" : ""}}>Chapter Wise Quiz</option>
                                </select>
                                @error('quiz_type')
                                    <span class="text-danger">Please select quiz Type</span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-sm-12" id="standard" style="display:none;">
                                    <div class="form-group">
                                        <label for="medium">Select Standard</label>
                                        <select name="quiz_standard" id="quiz_standard" class="form-control js-example-basic-multiple">
                                            <option disabled selected>Select Standard</option>
                                            @foreach($standards as $item)
                                                <option value="{{$item['id']}}" {{$quiz->standard_id == $item['id'] ? "selected" : ""}}>{{$item['name']}}</option>
                                            @endforeach
                                        </select>                              
                                    </div>
                                </div>
                                <div class="col-sm-12" id="subject" style="display:none;">
                                    <div class="form-group">
                                        <label for="medium">Select Subject</label>
                                        <select name="quiz_subject" id="quiz_subject" class="form-control js-example-basic-multiple ">
                                            <option disabled selected>Select Subject</option>
                                            @foreach($subjects as $item)
                                                <option value="{{$item->id}} " {{$quiz->subject_id == $item->id ? "selected" : ""}}>{{$item->standard->name}} | {{$item->name}}</option>
                                            @endforeach
                                        </select>                            
                                    </div>
                                </div>
                                <div class="col-sm-12" id="chapter" style="display:none;">
                                    <div class="form-group">
                                        <label for="medium">Select Chapter</label>
                                        <select name="quiz_chapter" id="quiz_chapter" class="form-control js-example-basic-multiple">
                                            <option disabled selected>Select Chapter</option>
                                            @foreach($chapters as $item)
                                                <option value="{{$item->id}}" {{$quiz->chapter_id == $item->id ? "selected" : ""}}>{{$item->subject->standard->name}} | {{$item->subject->name}} | {{$item->name}}</option>
                                            @endforeach
                                        </select>                             
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label for="quiz_desc">Quiz Description</label>
                                    <textarea name="quiz_desc" class="form-control" required>{{$quiz->quiz_desc}}</textarea>
                                </div>

                                <div class="form-group d-flex align-items-center ">
                                    <label for="marks" style="margin:0px;">Is Marks Manual?</label>
                                    <div class="form-check form-switch" style="margin-left:20px;">
                                        <input class="form-check-input" type="checkbox" id="marks_type" name="marks_type"  {{ $quiz->marks_type == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group" id="manual" style="display:none;">
                                    <label for="manual_marks">Enter Question Marking <small class="text-muted">(Marks/Question)</small></label>
                                    <input class="form-control" type="number" id="manual_marks" name="manual_marks" value="{{$quiz->manual_marks}}" step="0.01">
                                </div>
                                <div class="form-group d-flex align-items-center ">
                                    <label for="negative_marking_type" style="margin:0px;">Is Negative Marking?</label>
                                    <div class="form-check form-switch" style="margin-left:20px;">
                                        <input class="form-check-input" type="checkbox" id="negative_marking_type" name="negative_marking_type"  {{ $quiz->negative_marking_type == 1 ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group" id="negative" style="display:none;">
                                    <label for="negative_marking">Enter Question Negative Marking <small class="text-muted">(Marks/Question)</small></label>
                                    <input class="form-control" type="number" id="negative_marking" name="negative_marking" value="{{$quiz->negative_marking}}"  step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="total_quiz_time">Total Quiz Time <small class="text-muted">(In Minuts)</small></label>
                                    <input type="number" name="total_quiz_time" value="{{$quiz->total_quiz_time}}" class="form-control" required>
                                </div>
                                
                                
                                <div class="form-group">
                                    <label for="total_quiz_time">Add to Course</label>
                                    <select class="js-example-basic-multiple form-control" name="courses[]" multiple="multiple" >
                                        @if($quiz->course_id != "null") 
                                            @foreach($courses as $item)
                                                @foreach(json_decode($quiz->course_id) as $course_id)
                                                    <option value="{{$item->id}}" {{ $course_id == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                                @endforeach
                                            @endforeach
                                            
                                            @else
                                                @foreach($courses as $item) 
                                                    <option value="{{$item->id}}" >{{$item->name}}</option> 
                                                @endforeach 
                                        @endif
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="scheduler">Schedule Quiz</label>
                                    <input type="datetime-local" name="scheduler" id="scheduler" value="{{$quiz->scheduled_at}}" class="form-control" required>
                                </div>
                                <div class="form-group d-flex align-items-center ">
                                    <label for="is_published" style="margin:0px;">Publish?</label>
                                    <div class="form-check form-switch" style="margin-left:20px;">
                                        <input class="form-check-input" type="checkbox" id="is_published"  {{ $quiz->is_published == 1 ? 'checked' : '' }} name="is_published">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn btn-success text-white">Update</button>
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

        var now = new Date();

        // Format the date and time as required by the datetime-local input
        var formattedDate = now.toISOString().slice(0, 16);

        // Set the minimum attribute of the input element to the formatted date and time
        document.getElementById("scheduler").min = formattedDate;

        $('.js-example-basic-multiple').select2();

         $('#quiz_type').on('change', function(){
            let selectedQuizType = $(this).val();

            if(selectedQuizType == 'STWQ'){
                $('#standard').show();
                $('#subject').hide();
                $('#chapter').hide();
            }else if(selectedQuizType == 'SWQ'){
                $('#standard').hide();
                $('#subject').show();
                $('#chapter').hide();
            }else if(selectedQuizType == 'CWQ'){
                $('#standard').hide();
                $('#subject').hide();
                $('#chapter').show();
            }

         });
         $('#marks_type').on('change', function(){            
            $('#manual').toggle();
            var manualInput = $('#manual_marks');
            if (manualInput.is(':visible')) {
                manualInput.prop('required', true);
            } else {
                manualInput.removeAttr('required');
            }            
        });
        $('#negative_marking_type').on('change', function(){            
            $('#negative').toggle();
            var manualInput = $('#negative_marking');
            if (manualInput.is(':visible')) {
                manualInput.prop('required', true);
            } else {
                manualInput.removeAttr('required');
            }            
        });
        let type = "{{$quiz->type}}";
    
        if(type == 'STWQ'){
            $('#standard').show();
            $('#subject').hide();
            $('#chapter').hide();
        }else if(type == 'SWQ'){
            $('#standard').hide();
            $('#subject').show();
            $('#chapter').hide();
        }else if(type == 'CWQ'){
            $('#standard').hide();
            $('#subject').hide();
            $('#chapter').show();
        }

        let marks_type = "{{$quiz->marks_type}}";
        console.log("marks_type=" + marks_type);
        if (marks_type == 1) {
            var manualInput = $('#manual_marks');
            $('#manual').show(); // Add this line to make the element visible
            manualInput.prop('required', true);
        }
        let negative_marking_type = "{{$quiz->negative_marking_type}}";
        console.log("negative_marking_type=" + marks_type);
        if (marks_type == 1) {
            var manualInput = $('#negative_marking');
            $('#negative').show(); // Add this line to make the element visible
            manualInput.prop('required', true);
        }
    });
</script>
@endsection
