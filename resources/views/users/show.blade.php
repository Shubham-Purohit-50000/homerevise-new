@extends('backend.layout')
@section('title','Dashbord')
@section('content')
@php
    use Carbon\Carbon;
@endphp

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">User Details</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">user-detail</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/user/deregister/device', ['user'=>$user->id])}}" class="btn btn-danger text-white" onclick="return confirmDelete('Are you sure you want to remove registered device from user account? This action cannot be undone.')">Remove Register Device</a>
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
                    <div class="card-body">
                        <!-- title -->
                        <div class="text-center p-3">
                            <div>
                                <h4 class="card-title">User Profile</h4>
                            </div>
                            <div id="profile_img my-2">
                                <img src="{{asset('storage/'.$user->image )}}" alt="" class="rounded-circle shadow" style="width:120px;height:120px;">
                            </div>
                            <div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Name</div>
                                    <div>{{$user->name}}</div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Email</div>
                                    <div>{{$user->email}}</div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Phone</div>
                                    <div>{{$user->phone}}</div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Address</div>
                                    <div>{{$user->address}}</div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Standard</div>
                                    <div>{{$user->standard}}</div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <div>Registered Device</div>
                                    <div>{{$user->device_id}}</div>
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
                    <div class="card-body">
                        <!-- title -->
                        <div class="d-md-flex">
                            <div>
                                <h4 class="card-title">User Course List</h4>
                                <h5 class="card-subtitle">Overview of all user courses</h5>
                            </div>
                        </div>
                        <!-- title -->
                    </div>
                    <div class="table-responsive">
                        <table class="table v-middle">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-top-0">#C_ID</th>
                                    <th class="border-top-0">C Name</th>
                                    <th class="border-top-0">C Duration</th>
                                    <th class="border-top-0">Activation Date</th>
                                    <th class="border-top-0">Expire Date</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activations as $activation)
                                <tr>
                                    <td>{{$activation->course->id}}</td>
                                    <td>{{$activation->course->name}}</td>
                                    <td>{{$activation->course->duration ? $activation->course->duration."(months)" : $activation->course->access_count."(count)"}}</td>
                                    <td>
                                        <?php
                                            $activation_time = Carbon::parse($activation->activation_time);
                                            $activation_time = $activation_time->format('Y-M-d');
                                        ?>
                                        {{$activation_time}}
                                    </td>
                                    <td>
                                        <?php
                                            $expiry_date = Carbon::parse($activation->expiry_date);
                                            $expiry_date = $expiry_date->format('Y-M-d');
                                        ?>
                                        {{$expiry_date}}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#myModal_{{$activation->id}}">
                                            <span class="mdi mdi-eye"></span> Duration
                                        </button>
                                        <!-- modal started here -->
                                            <div class="modal fade" id="myModal_{{$activation->id}}">
                                                <div class="modal-dialog modal-sm">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Manage Course Duration</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    @php
                                                        $currentDate = date('Y-m-d');
                                                    @endphp
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <form action="{{url('admin/update/user/course/duration', ['activation'=>$activation->id])}}" method="post">
                                                            @csrf
                                                            <div class="form-group @if($activation->course['device_type']!='android_box') d-none @endif" >
                                                               <div class="row">
                                                                   <div class="col-sm-5">
                                                                       <label for="expiry_date" id="count">Access Count</label>
                                                                       <input type="number"  class="col-sm-5 form-control" readonly  value="{{$activation->course['access_count']}}">&nbsp;&nbsp;
                                                                   </div>
                                                                   <div class="col-sm-2">
                                                                       <p class="mt-4"><i class="fa fa-arrow-right"></i></p>
                                                                   </div>

                                                                   <div class="col-sm-5">
                                                                       <label for="expiry_date" id="count">Updated count</label>
                                                                       <input type="number" name="count" class="col-sm-5 form-control" id="count"/>
                                                                   </div>
                                                               </div>
                                                            </div>
                                                            <div class="form-group @if($activation->course['device_type']!='mobile') d-none @endif "  >
                                                                <label for="expiry_date">Exp Date</label>
                                                                <input type="date" name="expiry_date" class="form-control" id="expiry_date" value="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activation->expiry_date)->format('Y-m-d') }}" min="{{ $currentDate }}">                                                            </div>
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-success">update</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    <!-- Modal footer -->
{{--                                                    <div class="modal-footer">--}}
{{--                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                                                    </div>--}}

                                                </div>
                                                </div>
                                            </div>
                                        <!-- modal end here -->
                                    </td>
                                </tr>
                                @empty
                                    <h4 class="">Data Not Found!</h4>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
