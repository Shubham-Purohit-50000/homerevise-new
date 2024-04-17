@extends('backend.layout')
@section('title','Dashbord')
@section('content')
@php
    use Carbon\Carbon;
@endphp

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    #user_card{
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 1px 1px 20px #ccc;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: #fc0000!important;
        background-color: #fff!important;
        border-color: #fc0000 #fc0000 #fff!important;
    }
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Staff Details</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Staff</a></li>
                            <li class="breadcrumb-item active" aria-current="page">staff-detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{route('staff.edit', ['staff'=>$staff->id])}}"><button class="btn btn-info"><span class="mdi mdi-pen"></span> Edit</button></a>
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
            <div class="col-4">
                <div class="card">
                    <div class="card-body" id="user_card">
                        <!-- title -->
                        <div class="text-center p-3">
                            <div>
                                <h4 class="card-title">Staff Profile</h4>
                            </div>
                            <div id="profile_img my-2">
                                <img src="{{asset('storage/uploads/images/gallery/user.png')}}" alt="" class="rounded-circle shadow" style="width:120px;height:120px;">
                            </div>
                            <div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Name</div>
                                    <div>{{$staff->name}}</div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Email</div>
                                    <div>{{$staff->email}}</div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Phone</div>
                                    <div>{{$staff->phone}}</div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Address</div>
                                    <div>{{$staff->address}}</div>
                                </li> 
                            </ul>
                            </div>
                        </div>
                        <!-- title -->
                    </div>
                </div>
            </div>

            <div class="col-8">
                <div class="card">
                     
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
@section("scripts")
<script>

</script>
@endsection
