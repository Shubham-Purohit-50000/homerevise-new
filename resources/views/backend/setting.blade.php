@extends('backend.layout')
@section('title','Dashbord')
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
    body{
        background: #F6F9FF;
    }
    .card{
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    }
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Setting</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li> 
                            <li class="breadcrumb-item active" aria-current="page">Settings</li>
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

    <div class="container-fluid">
        <div class="row">
            @foreach ($settings as $setting)

                @if($setting->setting_option == 'app_maintainance')
                    @php
                        $item = json_decode($setting->value);
                    @endphp
                        <!-- column -->
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- title -->
                                    <div class="d-md-flex">
                                        <div>
                                            <h4 class="card-title">Update Maintainance Mode</h4>
                                        </div>
                                    </div>
                                    <!-- title -->
                                </div>
                                <div class="px-4 pb-4">
                                    <form action="{{url('admin/setting/update/maintainance/mode')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                        <label for="status">Status
                                            <span class="{{ $item->maintain_mode == 'true' ? 'text-success' : 'text-danger' }}">
                                                ({{$item->maintain_mode}})
                                            </span>
                                        </label>

                                            <select name="maintain_mode" id="status" class="form-control">
                                                <option value="true">Active</option>
                                                <option value="false">Deactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="message">Message</label>
                                            <input type="text" name="message" class="form-control" value="{{$item->message}}">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn btn-success text-white">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                @endif

                @if($setting->setting_option == 'app_version')
                    @php
                        $item = json_decode($setting->value);
                    @endphp
                        <!-- column -->
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- title -->
                                    <div class="d-md-flex">
                                        <div>
                                            <h4 class="card-title">Update Application Version</h4>
                                        </div>
                                    </div>
                                    <!-- title -->
                                </div>
                                <div class="px-4 pb-4">
                                    <form action="{{url('admin/setting/update/app/version')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="link">Download Link small ( {{url('/')}}/storage/uploads/apk/homerevise.apk )</label>
                                            <input type="text" name="link" class="form-control" id="link" value="{{$item->link}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="version">Version</label>
                                            <input type="text" name="version" class="form-control" id="version" value="{{$item->version}}">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn btn-success text-white">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                @endif

                @if($setting->setting_option == 'pages')
                    @php
                        $item = json_decode($setting->value);
                    @endphp
                        <!-- column -->
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- title -->
                                    <div class="d-md-flex">
                                        <div>
                                            <h4 class="card-title">Update Pages</h4>
                                        </div>
                                    </div>
                                    <!-- title -->
                                </div>
                                <div class="px-4 pb-4">
                                    <form id="pages" action="{{url('admin/setting/update/pages')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="privacy_policy">Privacy Policy</label>
                                            <textarea name="privacy_policy" id="privacypolicy" class="form-control" cols="30" rows="10">{{$item->privacy_policy}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="term_condition">Term & Condition</label>
                                            <textarea name="term_condition" id="terms" class="form-control" cols="30" rows="10">{{$item->term_condition}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="Support">Support</label>
                                            <textarea name="Support" id="support" class="form-control" cols="30" rows="10">{{$item->Support}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn btn-success text-white">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                @endif

                @if($setting->setting_option == 'announcements')
                    @php
                        $item = json_decode($setting->value);
                    @endphp
                        <!-- column -->
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- title -->
                                    <div class="d-md-flex">
                                        <div>
                                            <h4 class="card-title">Update Announcements</h4>
                                        </div>
                                    </div>
                                    <!-- title -->
                                </div>
                                <div class="px-4 pb-4">
                                    <form action="{{url('admin/setting/update/announcement')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="status">Status
                                                <span class="{{ $item->status == 'true' ? 'text-success' : 'text-danger' }}">
                                                    ({{$item->status}})
                                                </span>
                                            </label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="true">Active</option>
                                                <option value="false">Deactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="heading">Heading</label>
                                            <input type="text" name="heading" class="form-control" id="heading" value="{{$item->heading}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="body">Body</label>
                                            <textarea name="body"  id="body" class="form-control" cols="30" rows="10">{{$item->body}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="Announcement Image" style="width:70px">
                                                Image
                                            </label>
                                            <input type="file" name="image" class="form-control" id="image">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn btn-success text-white">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                @endif

                @if($setting->setting_option == 'base_url')
                    @php
                        $item = json_decode($setting->value);
                    @endphp
                        <!-- column -->
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- title -->
                                    <div class="d-md-flex">
                                        <div>
                                            <h4 class="card-title">Update Base-url</h4>
                                        </div>
                                    </div>
                                    <!-- title -->
                                </div>
                                <div class="px-4 pb-4">
                                    <form action="{{url('admin/setting/update/base_url')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="link">Url</label>
                                            <input type="text" name="url" class="form-control" id="link" value="{{$item->url}}">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn btn-success text-white">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                @endif
            @endforeach

            <!-- column -->
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <!-- title -->
                        <div class="d-md-flex">
                            <div>
                                <h4 class="card-title">Upload Android APK</h4>
                            </div>
                        </div>
                        <!-- title -->
                    </div>
                    <div class="px-4 pb-4">
                        <form id="upload-form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="file" name="file" class="form-control" id="file">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn btn-success text-white">Submit</button>
                            </div>
                        </form>
                        <div id="error-messages"></div>
                        <div class="progress" style="display: none;">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- column -->
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <!-- title -->
                            <div class="d-md-flex">
                                <div>
                                    <h4 class="card-title">Upload Windows APK</h4>
                                </div>
                            </div>
                            <!-- title -->
                        </div>
                        <div class="px-4 pb-4">
                            <form id="upload-form-win" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="file" class="form-control" id="file">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn btn-success text-white">Submit</button>
                                </div>
                            </form>
                            <div id="error-messages-win"></div>
                            <div class="progress-win" style="display: none;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <!-- title -->
                            <div class="d-md-flex">
                                <div>
                                    <h4 class="card-title">Upload TV APK</h4>
                                </div>
                            </div>
                            <!-- title -->
                        </div>
                        <div class="px-4 pb-4">
                            <form id="upload-form-tv" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="file" class="form-control" id="file">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn btn-success text-white">Submit</button>
                                </div>
                            </form>
                            <div id="error-messages-tv"></div>
                            <div class="progress-tv" style="display: none;">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                            </div>
                        </div>
                    </div>
                </div>

                
                @if($setting->setting_option == 'sponsor')
                        <!-- column -->
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <!-- title -->
                                    <div class="d-md-flex">
                                        <div>
                                            <h4 class="card-title">Update Sponsor api</h4>
                                        </div>
                                    </div>
                                    <!-- title -->
                                </div>
                                <div class="px-4 pb-4">
                                    <form action="{{url('admin/setting/update/sponsor/api')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                        <label for="status">Status
                                            <span class="{{ $setting->value == 'active' ? 'text-success' : 'text-danger' }}">
                                                ({{$setting->value}})
                                            </span>
                                        </label>

                                            <select name="sponsor_api" id="sponsor_api" class="form-control">
                                                <option value="active">Active</option>
                                                <option value="deactive">Deactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn btn-success text-white">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                @endif

            </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
 $(document).ready(function(){
        $('#privacypolicy').summernote({
            height:200,
            minHeight: 200,
            maxHeight: 200,
            disableResizeEditor: true
        });
        $('#terms').summernote({
            height:200,
            minHeight: 200,
            maxHeight: 200,
            disableResizeEditor: true
        });
        $('#support').summernote({
            height:200,
            minHeight: 200,
            maxHeight: 200,
            disableResizeEditor: true
        });
        $('#body').summernote({
            height:200,
            minHeight: 200,
            maxHeight: 200,
            disableResizeEditor: true
        });
 });
    // tinymce.init({
    //   selector: 'textarea',
    //   plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
    //   toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    //   tinycomments_mode: 'embedded',
    //   tinycomments_author: 'Author name',
    //   mergetags_list: [
    //     { value: 'First.Name', title: 'First Name' },
    //     { value: 'Email', title: 'Email' },
    //   ],
    //   ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant"))
    // });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        const uploadForm = $('#upload-form-win');
        const progressBar = $('.progress-win');
        const progressBarInner = progressBar.find('.progress-bar');
        const errorMessages = $('#error-messages-win'); // Add an element to display error messages

        uploadForm.on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '{{ url('admin/setting/update/window/apk') }}',
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

<script>
    $(document).ready(function () {
        const uploadForm = $('#upload-form-tv');
        const progressBar = $('.progress-tv');
        const progressBarInner = progressBar.find('.progress-bar');
        const errorMessages = $('#error-messages-tv'); // Add an element to display error messages

        uploadForm.on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '{{ url('admin/setting/update/tv/apk') }}',
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

<script>
    $(document).ready(function () {
        const uploadForm = $('#upload-form');
        const progressBar = $('.progress');
        const progressBarInner = progressBar.find('.progress-bar');
        const errorMessages = $('#error-messages'); // Add an element to display error messages

        uploadForm.on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: '{{ url('admin/setting/update/apk') }}',
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
