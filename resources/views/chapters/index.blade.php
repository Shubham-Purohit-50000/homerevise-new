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
                <h4 class="page-title">Chapter List</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li> 
                            <li class="breadcrumb-item active" aria-current="page">Chapter List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/chapters/create')}}" class="btn btn-danger text-white">+ Create Chapter</a>
                    <a href="{{url('admin/chapters/import-chapter')}}" class="btn btn-danger text-white">+ Import Chapter</a>

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
{{--                        <div class="d-md-flex justify-content-between">--}}
{{--                            <div>--}}
{{--                                <h4 class="card-title">All Chapter List</h4>--}}
{{--                                <h5 class="card-subtitle">Overview of all chapter</h5>--}}
{{--                            </div>--}}
{{--                            <div>--}}
{{--                               <!-- <form action="{{ route('chapters.index') }}" method="GET">--}}
{{--                                    <div class="form-group d-flex align-items-center">--}}
{{--                                        <input type="text" class="form-control" name="search" value="" placeholder="Search by Chapter Name">--}}
{{--                                        <button type="submit" class="btn btn-primary">Search</button>--}}
{{--                                    </div>--}}
{{--                                </form> -->--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <!-- title -->--}}
{{--                    </div>--}}
                    <div class="table-responsive">
                        <table class="table v-middle" id="chapters">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">State</th>
                                    <th class="border-top-0">Board</th>
                                    <th class="border-top-0">Medium</th>
                                    <th class="border-top-0">Standard</th>
                                    <th class="border-top-0">Subject</th>
                                    <th class="border-top-0">Chapter</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#chapters').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('/admin/chapters') !!}',
            columns: [
                { data: 'id', name: 'id' , orderable: true},
                { data: 'state_name', name: 'state_name' },
                { data: 'board_name', name: 'board_name' },
                { data: 'medium_name', name: 'medium_name' },
                { data: 'standard_name', name: 'standard_name' },
                { data: 'subject_name', name: 'subject_name' },
                { data: 'chapter_name', name: 'chapter_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
        });
    });
</script>

@endsection
