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
                    <a href="{{url('admin/add/user/key', ['user'=>$user->id])}}"><button class="btn btn-success"><span class="mdi mdi-plus"></span> Add Key</button></a> 
                    <a href="{{route('users.edit', ['user'=>$user->id])}}"><button class="btn btn-info"><span class="mdi mdi-pen"></span> Edit</button></a>
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
                    <div class="card-body" id="user_card">
                        <!-- title -->
                        <div class="text-center p-3">
                            <div>
                                <h4 class="card-title">User Profile</h4>
                            </div>
                            <div id="profile_img my-2">
                                <img src="{{asset('storage/uploads/images/gallery/user.png')}}" alt="" class="rounded-circle shadow" style="width:120px;height:120px;">
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
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-bs-toggle="tab" href="#user_courses" aria-selected="true" role="tab">User Course</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#app_usage" aria-selected="false" tabindex="-1" role="tab">App Usage</a>
                        </li> 
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#played_topics" aria-selected="false" tabindex="-1" role="tab">Played Topics Analytics</a>
                        </li> 
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" data-bs-toggle="tab" href="#quiz_analytics" aria-selected="false" tabindex="-1" role="tab">Quiz Analytics</a>
                        </li> 
                        @if(filled($user->database))
                        <li class="nav-item" role="presentation">
                            <a href="{{ url('admin/download-report/' . $user->id) }}" class="btn btn-primary">Download Report</a>
                        </li>
                        @endif
                    </ul>
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade show active" id="user_courses" role="tabpanel">
                            
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
                                            <th class="border-top-0">Expire Date/Count</th>
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
                                                {{$activation->course->duration ? $expiry_date : $activation->expiry_count."(remaining count)"}}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#myModal_{{$activation->id}}">
                                                    <span class="mdi mdi-eye"></span> Update
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
                                                                            <input type="number"  class="col-sm-5 form-control" readonly  value="{{$activation->expiry_count ? $activation->expiry_count : '0'}}">&nbsp;&nbsp;
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
                                                                        <input type="date" name="expiry_date" class="form-control" id="expiry_date" value="{{ $activation->expiry_date ?  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activation->expiry_date)->format('d-m-Y') : ''}}" min="{{ $currentDate }}">                                                            </div>
                                                                    <div class="form-group">
                                                                        <button type="submit" class="btn btn-success">update</button>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                            <!-- Modal footer -->
                                {{-- <div class="modal-footer">--}}
                                {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
                                {{-- </div>--}}

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
                        <div class="tab-pane fade" id="app_usage" role="tabpanel">
                            
                            <div class="card-body">
                                <!-- title -->
                                <div class="d-md-flex">
                                    <div>
                                        <h4 class="card-title">User App Usage</h4>
                                        <h5 class="card-subtitle">Overview of user's App Usage</h5>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                <table class="table v-middle">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="border-top-0">Time (m:s)</th>
                                            <th class="border-top-0">{{$time}} (Minutes)</th>
                                        </tr>
                                    </thead> 
                                </table>
                                <!-- title -->
                                 @if($appUsage!=null)
                                <table class="table v-middle">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="border-top-0">Subject</th>
                                            <th class="border-top-0">Topic</th>
                                            <th class="border-top-0">Chapter</th>
                                            <th class="border-top-0">Date</th>
                                            <th class="border-top-0">Duration</th>
                                        </tr>
                                    </thead> 
                                    <tbody>
                                    @forelse ($appUsage as $item)
                                        <tr class="bg-light">
                                            <td>{{$item['subject']}}</td>
                                            <td>{{$item['topic']}}</td>
                                            <td>{{$item['chapter']}}</td>
                                            <td>{{ \Carbon\Carbon::createFromTimestampMs($item['date'])->format('d-M-Y') }}</td>
                                            <td>
                                                @php
                                                    $minutes = $item['duration_minutes'];
                                                    $days = floor($minutes / (24 * 60));
                                                    $hours = floor(($minutes % (24 * 60)) / 60);
                                                    $remainingMinutes = $minutes % 60;
                                                @endphp

                                                @if($days) {{$days}} days, @endif
                                                @if($hours) {{$hours}} hrs, @endif
                                                {{ $remainingMinutes }} min
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">No app usage data available.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div> 
                        </div> 
                        <div class="tab-pane fade" id="played_topics" role="tabpanel">
                            <div class="card-body">
                                <!-- title -->
                                <div class="d-md-flex">
                                    <div>
                                        <h4 class="card-title">User Played Topics</h4>
                                        <h5 class="card-subtitle">Overview of user's Played Topics</h5>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table v-middle">
                                        <thead>
                                            <tr class="bg-light">
                                                <th class="border-top-0">Sr.No</th>
                                                <th class="border-top-0">Subject</th>
                                                <th class="border-top-0">Chapter</th>
                                                <th class="border-top-0">Topic</th>
                                                <th class="border-top-0">Duration(minutes)</th>
                                                <th class="border-top-0">Total Topics</th> 
                                                <th class="border-top-0">Date</th> 
                                            </tr>
                                        </thead> 
                                        <tbody>
                                        @if(filled($user->database))
                                            @php
                                                $json = json_decode($user->database)->json;
                                                $decodedJson = json_decode($json);
                                                $played_topics = $decodedJson->played_topics ?? []; // Fallback to empty array if null
                                            @endphp
                                            @if(!empty($played_topics))
                                                @foreach($played_topics as $item)
                                                    <tr class="bg-light">
                                                        <td> {{$item->id}}</td>
                                                        <td> {{$item->subject}}</td>
                                                        <td> {{$item->chapter}}</td>
                                                        <td> {{$item->topic}}</td>
                                                        <td> {{$item->duration_minutes}}</td>
                                                        <td> {{$item->total_topics}}</td>
                                                        <td> {{ \Carbon\Carbon::createFromTimestampMs($item->date)->format('d-M-Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7"><center><h3>No topics played yet.</h3></center></td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="7"><center><h3>Data not available :(</h3></center></td>
                                            </tr>
                                        @endif
                                        </tbody> 
                                    </table>
                                <!-- title -->
                            </div>
                        </div> 
                        </div> 

                        <div class="tab-pane fade" id="quiz_analytics" role="tabpanel">
                            
                            <div class="card-body">
                                <!-- title -->
                                <div class="d-md-flex">
                                    <div>
                                        <h4 class="card-title">User Quiz Analytics</h4>
                                        <h5 class="card-subtitle">Overview of user's Quiz Analytics</h5>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table v-middle">
                                        <thead>
                                            <tr class="bg-light">
                                                <th class="border-top-0">Sr.No</th>
                                                <!-- <th class="border-top-0">Subject</th> -->
                                                <th class="border-top-0">Quiz Name</th>
                                                <th class="border-top-0">Total Questions</th>
                                                <th class="border-top-0">Questions Attempted</th>
                                                <th class="border-top-0">Marks Earned</th> 
                                                <th class="border-top-0">Total Marks</th> 
                                                <th class="border-top-0">Right Questions</th> 
                                                <th class="border-top-0">Wrong Questions</th> 
                                                <th class="border-top-0">Created At</th> 
                                            </tr>
                                        </thead> 
                                        <tbody>
                                        @if(filled($user->database))
                                            @php
                                                try {
                                                    $json = json_decode($user->database);
                                                    $nestedJson = $json->json ?? null; // Ensure 'json' exists in the first level
                                                    $quiz_analytics = $nestedJson->quiz_analytics ?? null; // Safely access 'quiz_analytics'
                                                } catch (Exception $e) {
                                                    $quiz_analytics = null; // Handle invalid JSON or unexpected errors
                                                }
                                            @endphp
                                            @if(filled($quiz_analytics) && is_array($quiz_analytics))
                                                @foreach($quiz_analytics as $item)
                                                    <tr>
                                                        <td>{{ $item->id ?? 'N/A' }}</td>
                                                        <td>{{ $item->quiz_name ?? 'N/A' }}</td>
                                                        <td>{{ $item->total_questions ?? 'N/A' }}</td>
                                                        <td>{{ $item->questions_attempted ?? 'N/A' }}</td>
                                                        <td>{{ $item->marks_earned ?? 'N/A' }}</td>
                                                        <td>{{ $item->total_marks ?? 'N/A' }}</td>
                                                        <td>{{ $item->right_questions ?? 'N/A' }}</td>
                                                        <td>{{ $item->wrong_questions ?? 'N/A' }}</td>
                                                        <td>{{ isset($item->date) ? date('Y-m-d H:i:s', $item->date / 1000) : 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="9">
                                                        <center><h3>Data not available :(</h3></center>
                                                    </td>
                                                </tr>
                                            @endif
                                        @else
                                            <tr>
                                                <td colspan="9">
                                                    <center><h3>Data not available :(</h3></center>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody> 
                                    </table>
                                <!-- title -->
                            </div>
                        </div> 
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
