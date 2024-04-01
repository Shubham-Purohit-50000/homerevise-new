@extends('backend.layout')
@section('title','Quiz')
@section('content')
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Quiz</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li> 
                            <li class="breadcrumb-item active" aria-current="page">Quiz Questions List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/quizes/create')}}" class="btn btn-danger text-white">+ Create Quiz</a>
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
{{--                                <h4 class="card-title">All Quiz List</h4>--}}
{{--                                <h5 class="card-subtitle">Overview of all Quiz</h5>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- title -->--}}
{{--                    </div>--}}
                    <div class="table-responsive">
                        <table class="table v-middle my_table">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">Quiz</th>
                                    <th class="border-top-0">Category</th>
                                    <th class="border-top-0">No of Questions</th>
                                    <th class="border-top-0">Total Marks</th>
                                    <th class="border-top-0">Total Duration</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quiz as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>
                                        @switch($item->type)
                                            @case('SWQ')
                                                Subject Wise Quiz
                                                @break
                                            @case('CWQ')
                                                Chapter Wise Quiz
                                                @break
                                            @default
                                                Standard Wise Quiz
                                        @endswitch
                                    </td>
                                    <td>{{$item->questions($item->id)}}</td>
                                    <td>
                                        @if($item->marks_type && ($item->questions($item->id)) > 0)
                                            {{$item->questions($item->id) * $item->manual_marks}}
                                        @elseif( ($item->questions($item->id)) > 0)
                                            {{$item->marks($item->id)}}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>{{$item->total_quiz_time}}</td>
                                    <td>
                                    <a href="{{ route('quizes.add-questions', ['id' => $item->id]) }}">
                                        <button class="btn btn-sm btn-info">
                                            + Add Question
                                        </button>
                                    </a>
                                    <a href="{{ route('quizes.edit', ['quize' => $item->id]) }}">
                                        <button class="btn btn-sm btn-info">
                                            <span class="mdi mdi-pen"></span> Edit
                                        </button>
                                    </a>
                                        <form action="{{ route('quizes.destroy', ['quize' => $item->id]) }}" method="POST" class="d-inline">
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
