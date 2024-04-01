@extends('backend.layout')
@section('title','Gallery')
@section('content')

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
                <h4 class="page-title">Gallery</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn mx-1">
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#imageBulkUpload" class="btn btn-danger text-white">Upload Image</a>
                </div>
            </div>
            <div class="modal fade" id="imageBulkUpload" tabindex="-1" role="dialog" aria-labelledby="imageBulkUploadLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageBulkUploadLabel">Image Bulk Upload</h5> 
                        </div>
                        <form id="upload-form-image" enctype="multipart/form-data">
                            <div class="modal-body">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="file" class="form-control" id="file" required>
                                </div> 
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
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
            <div class="card">                
                <div class="table-responsive">
                    <table class="table v-middle my_table">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-top-0">#ID</th>
                                <th class="border-top-0">Image</th>
                                <th class="border-top-0">Image Name</th>
                                <th class="border-top-0">Image Url</th>
                                <th class="border-top-0">Image Type</th>
                                <th class="border-top-0">Image Size</th>
                                <th class="border-top-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($galleryImages as $key=>$image)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td><img src="{{$image->url}}" style="width:45px"></td>
                                    <td>{{$image->name}}</td>
                                    <td><a href="{{$image->url}}" target="_blank" class="btn btn-sm btn-default">view</a></td>
                                    <td>{{$image->extension}}</td>
                                    <td>{{$image->size}}</td>
                                    <td>
                                        <a href="{{route('gallery.edit', ['id'=>$image->id])}}" ><button class="btn btn-sm btn-info"><span class="mdi mdi-pen"></span> Edit</button></a>
                                        <a href="javascript:void(0);">
                                            <button onclick="copyToClipboard('{{$image->url}}', 'copyButton{{$key}}')" id="copyButton{{$key}}" class="btn btn-sm btn-info">
                                                <span class="mdi mdi-content-copy"></span> Copy Image
                                            </button>
                                        </a>
                                        <form action="{{ route('gallery.destroy', ['id' => $image->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm btn-danger text-white" onclick="return confirmDelete('On delete this record, the image will be deleted with its assosiate questions.')"><span class="mdi mdi-delete-empty"></span> Delete</button>
                                        </form></td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> 
        </div> 
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script> 
    $(document).ready(function () { 

    });
    function copyToClipboard(url,buttonId){
        navigator.clipboard.writeText(url).then(function() {
            // alert("URL copied to clipboard: " + url);
            document.getElementById(buttonId).innerText = "Image Copied";
        }, function(err) {
            alert("Failed to copy URL to clipboard: " + err);
        });
    }
    const uploadForm = $('#upload-form-image'); 

    uploadForm.on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this); 
        $.ajax({
            url: '{{ url('admin/gallery/bulk-upload') }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false, 
            success: function (response) {
                console.log(response); 
                if (response.errors) {
                    
                } else {
                    window.location.reload(); // Refresh the window
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                // Handle any errors here
                if (xhr.responseJSON) {
                     
                } else {
                     
                }
            }
        });
    });
</script>



@endsection