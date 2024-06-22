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
                    <div class="table-responsive">
                        <table class="table v-middle" id="questions">
                            <thead>
                                <tr class="bg-light">
                                    <th class="border-top-0">#ID</th>
                                    <th class="border-top-0">Questions</th>
                                    <th class="border-top-0">Questions Image</th>
                                    <th class="border-top-0">Type</th>
                                    <th class="border-top-0">Standard</th>
                                    <th class="border-top-0">Subject</th>
                                    <th class="border-top-0">Correct Marks</th>
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

          $('#questions').DataTable({
              processing: true,
              serverSide: true,
              ajax: '{!! url('/admin/questions') !!}',
              paging: true,  // Enable paging
              pageLength: 25,
              columns: [
                  {data: 'id', name: 'id', orderable: true},
                  {data: 'questions', name: 'questions'},
                  {data: 'questionsImage', name: 'questionsImage', orderable: false, searchable: false,
                   render: function(data, type, row) {
                      return data ? '<img src="' + data + '" width="50px">' : 'NA';
                   }},
                  {data: 'question_type', name: 'question_type'},
                  {data: 'standard.name', name: 'standard.name'},
                  {data: 'subject.name', name: 'subject.name'},
                  {data: 'correct_marks', name: 'correct_marks'},
                  {data: 'action', name: 'action', orderable: false, searchable: false},
              ],

          });
      });

    </script>
</div>
@endsection