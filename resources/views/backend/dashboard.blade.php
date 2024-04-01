@extends('backend.layout')
@section('title','Dashbord')
@section('content')

<style>
    .left-border{
        border-left: 3px solid;
    }
    .info_card h3{
        font-size: 1.2rem;
    }
    body{
        background: #F6F9FF;
    }
    .card{
        box-shadow: 20px 20px 50px -30px #AFD6FF;
    }
</style>
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Dashboard</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Library</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 text-end" >
                <div class="text-end upgrade-btn mx-1 btn-group">
                    <a href="{{url('admin/courses/create')}}" class="btn btn-success mb-4 ">+ Create Course </a>&nbsp;
                    <a href="https://demo.homerevise.co/admin/courses" class="btn btn-primary mb-4"><i class="mdi mdi-account"></i>&nbsp; Old User</a>
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
    <div class="container-fluid" style="background: #F6F9FF !important;">

        <div class="row">

            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/states')}}">
                    <div class="card p-2  rounded">
                        <div class="card-body">
                            <h3 class="text-success">State</h3>
                            <h5 class="mdi mdi-web text-dark"> {{App\Models\State::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 text-center ">
                <a href="{{url('admin/boards')}}">
                    <div class="card p-2  rounded">
                        <div class="card-body">
                            <h3 class="text-success">Board</h3>
                            <h5 class="mdi mdi-city text-dark"> {{App\Models\Board::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/mediums')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Mediums</h3>
                            <h5 class="mdi mdi-bulletin-board text-dark"> {{App\Models\Medium::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/standards')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Standards</h3>
                            <h5 class="mdi mdi-translate-variant text-dark"> {{App\Models\Standard::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/subjects')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Subjects</h3>
                            <h5 class="mdi mdi-book-multiple text-dark"> {{App\Models\Subject::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/chapters')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Chapters</h3>
                            <h5 class="mdi mdi-book-open-page-variant text-dark"> {{App\Models\Chapter::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/topics')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Topics</h3>
                            <h5 class="mdi mdi-file text-dark"> {{App\Models\Topic::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/subtopics')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Sub-Topics</h3>
                            <h5 class="mdi mdi-file-document-box text-dark"> {{App\Models\Subtopic::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/courses')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Courses</h3>
                            <h5 class="mdi mdi-bag-personal text-dark"> {{App\Models\Course::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/users')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Users</h3>
                            <h5 class="mdi mdi-account text-dark"> {{App\Models\User::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/quizes')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Quizzes</h3>
                            <h5 class="mdi mdi-file-document text-dark"> {{App\Models\Quiz::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3 text-center">
                <a href="{{url('admin/questions')}}">
                    <div class="card p-2 rounded">
                        <div class="card-body">
                            <h3 class="text-success">Questions</h3>
                            <h5 class="mdi mdi-note-plus text-dark"> {{App\Models\Questions::count()}}</h5>
                        </div>
                    </div>
                </a>
            </div>

        </div>

    </div>

</div>
@endsection
