@extends('backend.layout')
@section('title','Tools')
@section('content')
<script src="https://cdn.tiny.cloud/1/adnb18asr5ik4nc9qvkvngwb62t443tqa1kmqe9x08o0av2e/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<style>
    .left-border{
        border-left: 3px solid;
    }
    .info_card h3{
        font-size: 1.2rem;
    }
    .progress{
        height: 15px;
    }
    #pages{
        height: 588px;
        overflow: scroll;
        padding-right:15px;
    }
    .mdi{
        font-size: 50px;
    }
    .title{
        font-size: 18px;
        padding-bottom: 20px;
    }
    .card{
        padding:25px;
    }
    body{
        background: #F6F9FF;
    }
    .card{
        box-shadow: 20px 20px 50px -30px #AFD6FF;
    }
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Tools</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li> 
                            <li class="breadcrumb-item active" aria-current="page">Tools</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn mx-1">
                    <a href="{{url('admin/dashboard')}}" class="btn btn-danger text-white">Dashboard</a>
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

    <div class="container-fluid" style="background: #F6F9FF !important;">
        <div class="row">
            <div class="col-sm-4">
                <div class="card text-center p-2  rounded">
                    <div class="icon">
                        <i class="mdi mdi-android"></i>
                    </div>
                    <div class="title">
                        HomeRevise Android Application
                    </div>
                    <div class="donwnload-btn">
                        <a href="{{url('/')}}/storage/uploads/apk/homerevise.apk" class="btn btn-primary btn-lg">Download Now</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card p-2  rounded text-center">
                    <div class="icon">
                        <i class="mdi mdi-microsoft-windows"></i>
                    </div>
                    <div class="title">
                        HomeRevise Windows Application
                    </div>
                    <div class="donwnload-btn">
                        <a href="{{url('/')}}/storage/uploads/apk/homerevise_win.apk" class="btn btn-primary btn-lg">Download Now</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card p-2  rounded text-center">
                    <div class="icon">
                        <i class="mdi mdi-youtube-tv"></i>
                    </div>
                    <div class="title">
                        HomeRevise Tv Application
                    </div>
                    <div class="donwnload-btn">
                        <a href="{{url('/')}}/storage/uploads/apk/homerevise_tv.apk" class="btn btn-primary btn-lg">Download Now</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <!-- title -->
                        <div class="d-md-flex">
                            <div>
                                <h4 class="card-title">Select Image</h4>
                            </div>
                        </div>
                        <!-- title -->
                    </div>
                    <div class="px-4 pb-4">
                        <form id="upload-form-image" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="file" name="file" class="form-control" id="file" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" id="name" placeholder="File Name.." required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn btn-success text-white">Get Image Url</button>
                            </div>
                        </form>
                        <div id="imgURL" style="display:none;">
                            <div class="input-box d-flex justify-content-between" >
                                <input type="text" readonly style="    width: 85%;" id="img_url">
                                <a href="javascript:void(0);" class="btn btn btn-success text-white"  id="copyLink" data-clipboard-target="#img_url">Copy Link</a>
                            </div>
                        </div>

                        <div id="error-messages-tv"></div>
                        <div class="progress-tv" style="display: none;">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new ClipboardJS('#copyLink');
    });
    $(document).ready(function () {
        const uploadForm = $('#upload-form-image');
        const progressBar = $('.progress-win');
        const progressBarInner = progressBar.find('.progress-bar');
        const errorMessages = $('#error-messages-win'); // Add an element to display error messages

        uploadForm.on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            $('#img_url').val("");
            $.ajax({
                url: '{{ url('admin/setting/upload/image') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    const xhr = new window.XMLHttpRequest();

                    // Upload progress
                    xhr.upload.addEventListener('progress', function (event) {
                        if (event.lengthComputable) {
                            const percentCompleted = Math.round((event.loaded * 100) / event.total);
                            progressBar.css('display', 'block');
                            progressBarInner.css('width', percentCompleted + '%');
                            progressBarInner.text(percentCompleted + '%');
                        }
                    }, false);

                    return xhr;
                },
                success: function (response) {
                    console.log(response);
                    // Check if the response contains an "errors" key
                    if (response.errors) {
                        // Display the error messages
                        errorMessages.html('<div class="alert alert-danger">' + response.message + '</div>');
                    } else {
                        // Handle the success response here
                        $('#imgURL').show();
                        $('#img_url').val(response.message);
                        errorMessages.html('<div class="alert alert-success">' + response.message + '</div>');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // Handle any errors here
                    if (xhr.responseJSON) {
                        // Display the error message from the JSON response
                        errorMessages.html('<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>');
                    } else {
                        // Fallback error message
                        errorMessages.html('<div class="alert alert-danger">An error occurred: ' + errorThrown + '</div>');
                    }
                }
            });
        });
    });
</script>



@endsection
