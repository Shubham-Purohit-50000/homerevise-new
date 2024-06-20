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
    .imgContainer{
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        border-radius: 5px;
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
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="/admin/gallery">Gallery</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Update Gallery</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn mx-1">
                    <a href="/admin/gallery"  class="btn btn-danger text-white">Back</a>
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
                <form id="upload-form-image" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6"> 
                            <div class="form-group">
                                <label>Image Name</label>
                                <input type="text" name="name" value="{{$gallery->name}}" class="form-control" id="name" required>
                                <input type="hidden" name="oldName" value="{{$gallery->name}}" class="form-control" id="oldName" required>
                                <input type="hidden" name="oldExt" value="{{$gallery->extension}}" class="form-control" id="oldExt" required>
                                <input type="hidden" name="id" value="{{$gallery->id}}" class="form-control" id="id" required>
                            </div>  
                            <div class="form-group">
                                <label>Image Type</label>
                                <input type="text" name="type" value="{{$gallery->extension}}" class="form-control" id="type" disabled>
                            </div>  
                            <div class="form-group">
                                <label>Image Size</label>
                                <input type="text" name="size" value="{{$gallery->size}}" class="form-control" id="size" disabled>
                            </div>   
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="updatedImageFile" class="form-control" id="file">
                            </div>  
                            <div class="modal-footer"> 
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                        <div class="col-sm-6 imgContainer">
                            <img src="{{$gallery->url}}" style="width:200px;">
                        </div>
                    </div>
                    
                </form>
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
    const uploadForm = $('#upload-form-image'); 

    uploadForm.on('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this); 
        $.ajax({
            url: '{{ url('admin/gallery/update') }}',
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