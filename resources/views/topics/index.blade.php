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
                <h4 class="page-title">Topic List</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li> 
                            <li class="breadcrumb-item active" aria-current="page">Topic List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">

                    <a href="{{url('admin/topics/create')}}" class="btn btn-danger text-white">+ Create Topic</a>
                    <a href="{{ url('admin/topics/show') }}" class="btn btn-danger text-white">+ Import Topic</a>
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
                    <div class="table-responsive">
                        <table class="table v-middle" id="topics">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-top-0">ID</th>
                                    <th class="border-top-0">State</th>
                                    <th class="border-top-0">Board</th>
                                    <th class="border-top-0">Medium</th>
                                    <th class="border-top-0">Standard</th>
                                    <th class="border-top-0">Subject</th>
                                    <th class="border-top-0">Chapter</th>
                                    <th class="border-top-0">Topic</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
      $(document).ready(function() {

          $('table#topics').DataTable({
              processing: true,
              serverSide: true,
              ajax: '{!! url('/admin/topics') !!}',
              paging: true,  // Enable paging
              pageLength: 25,
              columns: [
                  {data: 'id',  name: 'id' , orderable: true} ,
                  { data: 'state_name', name: 'states.name' },
                  { data: 'board_name', name: 'boards.name' },
                  { data: 'medium_name', name: 'mediums.name' },
                  { data: 'standard_name', name: 'standards.name' },
                  { data: 'subject_name', name: 'subjects.name' },
                  { data: 'chapter_name', name: 'chapters.name' },
                  { data: 'heading', name: 'topics.heading' },
                  { data: 'action', name: 'action', orderable: false, searchable: false },
              ],

          });
      });

</script>
</div>
@endsection
