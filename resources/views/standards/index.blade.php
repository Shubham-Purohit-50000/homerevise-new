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
                <h4 class="page-title">Stnadard List</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Standard List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/standards/create')}}" class="btn btn-danger text-white">+ Create Standard</a>
                    <a href="{{url('admin/standards/import-standard')}}" class="btn btn-danger text-white">+ Import Standard</a>
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
{{--                    <div class="card-body">--}}
{{--                        <!-- title -->--}}
{{--                        <div class="d-md-flex">--}}
{{--                            <div>--}}
{{--                                <h4 class="card-title">All Standard List</h4>--}}
{{--                                <h5 class="card-subtitle">Overview of all standards</h5>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- title -->--}}
{{--                    </div>--}}
                    <div class="table-responsive">
                        <table class="table v-middle my_table">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">State</th>
                                    <th class="border-top-0">Board</th>
                                    <th class="border-top-0">Medium</th>
                                    <th class="border-top-0">Standard</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($standards as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->medium->board->state->name}}</td>
                                    <td>{{$item->medium->board->name}}</td>
                                    <td>{{$item->medium->name}}</td>
                                    <td>{{$item->name}}</td>
                                    <td>
                                        <a href="{{url('admin/courses/create', ['type' => 'standard', 'standard_id' => $item->id])}}"><button class="btn btn-sm btn-success"><span class="mdi mdi-plus"></span> Add Course</button></a>
                                        <a href="{{route('standards.edit', ['standard'=>$item->id])}}"><button class="btn btn-sm btn-info"><span class="mdi mdi-pen"></span> Edit</button></a>
                                        <form action="{{ route('standards.destroy', ['standard' => $item->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger text-white" onclick="return confirmDelete('On delete this record, All data such as Subjects, courses, topics etc under this record will be deleted')"><span class="mdi mdi-delete-empty"></span> Delete</button>
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

    </div>

</div>
@endsection
