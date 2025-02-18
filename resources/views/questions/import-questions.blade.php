@extends('backend.layout')
@section('title','Dashboard')
@section('content')

<style>
    .left-border {
        border-left: 3px solid;
    }
    .info_card h3 {
        font-size: 1.2rem;
    }
    #optioncontainer {
        padding: 10px;
        border: 2px dashed #cccccc70;
    }
    .content ul > li {
        font-size: 16px;
        line-height: 1.5;
        padding-top: 10px;
    }
    li > p {
        margin: 0;
        padding-top: 5px;
    }
    #progressBar {
        width: 0;
        height: 20px;
        background-color: green;
    }
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Import Question</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/questions">Questions</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Import Questions</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/questions')}}" class="btn btn-danger text-white">Question List</a>
                    <a href="{{url('/storage/uploads/questionbulkupload.xlsx')}}" class="btn btn-danger text-white">Download Sample</a>
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
                        <h3 class="mb-3">Add Files</h3>
                        <span class="text-danger">Note: Upload file with maximum 10,000 Questions only</span>
                        <form id="uploadForm" method="POST" enctype="multipart/form-data">
                            @csrf 
                            <div class="form-group" id="correct_answere">
                                <label for="correct_ans">Add Question File</label>
                                <input type="file" name="questionsFile" class="form-control" accept=".xls,.xlsx,.ods">
                                @error('questionsFile')
                                    <span class="text-danger">Please select question file.</span>
                                @enderror
                                @error('error')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success text-white">Submit</button>
                            </div>
                        </form>
                        <div id="message"></div>
                        <div class="progress mt-3">
                            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('#uploadForm').on('submit', function(event) {
                                    event.preventDefault();

                                    var formData = new FormData(this);

                                    $.ajax({
                                        url: '{{ url("admin/questions/bulk-upload") }}',
                                        method: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        xhr: function() {
                                            var xhr = new window.XMLHttpRequest();
                                            xhr.upload.addEventListener('progress', function(event) {
                                                if (event.lengthComputable) {
                                                    var percentComplete = Math.round((event.loaded / event.total) * 100);
                                                    $('#progressBar').css('width', percentComplete + '%');
                                                    $('#progressBar').attr('aria-valuenow', percentComplete);
                                                    $('#progressBar').text(percentComplete + '%');
                                                }
                                            }, false);
                                            return xhr;
                                        },
                                        success: function(response) {
                                            toastr.success(response.success);
                                            $('#message').html('<div class="alert alert-success">' + response.success + '</div>');
                                            $('#progressBar').css('width', '0%');
                                            $('#progressBar').text('0%');
                                        },
                                        error: function(response) {
                                            toastr.error(error);
                                            var error = response.responseJSON.error;
                                            $('#message').html('<div class="alert alert-danger">' + error + '</div>');
                                            $('#progressBar').css('width', '0%');
                                            $('#progressBar').text('0%');
                                        }
                                    });
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-6 card"> 
                <div class="">
                    <div class="py-4 px-4 pb-4">
                        <h2 class="mb-3">Important Notes:</h2>
                        <div class="content">
                            <ul>
                                <li>Please Download the Questions Sample File from the top right corner for better understanding.</li>
                                <li>In Question Type please follow : <br>
                                    <p><strong>MSA </strong>for Multiple Choice Single Answer.</p>
                                    <p><strong>TOF </strong>for True or False.</p>
                                    <p><strong>FIB </strong>for Fill in the Blanks.</p>
                                    <p><strong>SAQ </strong> for Short Answer Question.</p>
                                </li>
                                <li>For adding the image in questions kindly use this format:- <br>
                                    &lt;img src=http://homerevise.co/storage/uploads/images/questions/images/imagename.extension> <br>
                                    Or copy the image URL directly from the <a href="/admin/gallery">Gallery Section.</a>
                                </li>
                                <li>Kindly add all the related fields carefully.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>    
</div>

@endsection