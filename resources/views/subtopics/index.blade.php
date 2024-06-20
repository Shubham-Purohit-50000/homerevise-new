@extends('backend.layout')
@section('title','Dashbord')
@section('content')

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">SubTopic List</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li> 
                            <li class="breadcrumb-item active" aria-current="page">Sub Topic List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">

                    <a href="{{url('admin/subtopics/create')}}" class="btn btn-danger text-white">+ Create SubTopic</a>
                    <a href="{{ url('admin/subtopics/show') }}" class="btn btn-danger text-white">+ Import SubTopic</a>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- title -->
                        <div class="d-md-flex justify-content-between">
{{--                            <div>--}}
{{--                                <h4 class="card-title">All SubTopic List</h4>--}}
{{--                                <h5 class="card-subtitle">Overview of all Subtopic</h5>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                                <form action="{{ route('subtopics.index') }}" method="GET">--}}
{{--                                    <div class="form-group d-flex align-items-center">--}}
{{--                                        <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Search by Subtopic Name">--}}
{{--                                        <button type="submit" class="btn btn-primary">Search</button>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                            </div>--}}
                        </div>
                        <!-- title -->
                    </div>
                    <div class="table-responsive">
                        <table class="table v-middle my_table">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">State</th>
                                    <th class="border-top-0">Board</th>
                                    <th class="border-top-0">Medium</th>
                                    <th class="border-top-0">Standard</th>
                                    <th class="border-top-0">Subject</th>
                                    <th class="border-top-0">Chapter</th>
                                    <th class="border-top-0">Topic</th>
                                    <th class="border-top-0">SubTopic</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subtopics as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->topic->chapter->subject->standard->medium->board->state->name}}</td>
                                    <td>{{$item->topic->chapter->subject->standard->medium->board->name}}</td>
                                    <td>{{$item->topic->chapter->subject->standard->medium->name}}</td>
                                    <td>{{$item->topic->chapter->subject->standard->name}}</td>
                                    <td>{{$item->topic->chapter->subject->name}}</td>
                                    <td>{{$item->topic->chapter->name}}</td>
                                    <td>{{$item->topic->heading}}</td>
                                    <td>{{$item->heading}}</td>
                                    <td>
                                        <a href="{{route('subtopics.edit', ['subtopic'=>$item->id])}}"><button class="btn btn-sm btn-info"><span class="mdi mdi-pen"></span> Edit</button></a>
                                        <form action="{{ route('subtopics.destroy', ['subtopic' => $item->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger text-white" onclick="return confirmDelete('On delete this record, All data such as Courses and activation etc under this record will be deleted')"><span class="mdi mdi-delete-empty"></span> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            {{ $subtopics->links() }}
        </div>

    </div>

</div>
@endsection
