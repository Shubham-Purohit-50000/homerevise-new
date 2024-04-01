@extends('backend.layout')
@section('title','Question')
@section('content')
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Questions</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li> 
                            <li class="breadcrumb-item active" aria-current="page"> Questions List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/questions/import-questions')}}" class="btn btn-danger text-white">+ Import Questions</a>
                    <a href="{{url('admin/questions/create')}}" class="btn btn-danger text-white">+ Create Questions</a>
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
{{--                                <h4 class="card-title">All Questions List</h4>--}}
{{--                                <h5 class="card-subtitle">Overview of all Questions</h5>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- title -->--}}
{{--                    </div>--}}
                    <div class="table-responsive">
                        <table class="table v-middle my_table">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">Questions</th>
                                    <th class="border-top-0">Type</th>
                                    <th class="border-top-0">Standard</th>
                                    <th class="border-top-0">Subject</th>
                                    <th class="border-top-0">Correct Marks</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td style="width: 25%;">{!!$item->questions!!}</td>
                                    <td>{{$item->question_type}}</td>
                                    <td>{{$item->standard->name}}</td>
                                    <td>{{$item->subject->name}}</td>
                                    <td>{{$item->correct_marks}}</td>
                                    <td>
                                        <a href="{{route('questions.edit', ['question'=>$item->id])}}"><button class="btn btn-sm btn-info"><span class="mdi mdi-pen"></span> Edit</button></a>
                                        <form action="{{ route('questions.destroy', ['question' => $item->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger text-white" onclick="return confirmDelete('On delete this record, the quiz will be deleted with its assosiate questions.')"><span class="mdi mdi-delete-empty"></span> Delete</button>
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
