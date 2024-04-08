<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-16">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') | HomeRevise</title>

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('backend/assets/images/favicon.png')}}">
    <!-- Custom CSS -->
    <link href="{{asset('backend/assets/libs/chartist/dist/chartist.min.css')}}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('backend/dist/css/style.min.css')}}" rel="stylesheet">
    <!-- JQUERY CDN-->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> -->

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- cdn for toaster -->
    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.css" rel="stylesheet" />

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.js.map"></script>

     <!-- CSS -->
     <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet" />

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

    <!-- datatable liberary -->

    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @if(!auth('admin')->check())
    <style>
        #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper{
            margin-left:0px!important;
        }
    </style>
    @endif

    <style>
        .page-wrapper {
            background: #ffffff!important;
            position: relative;
        }
        .table-responsive{
            padding: 0px 20px!important;
        }
        .left-sidebar{
            position: fixed;
            overflow-y: auto;
        }
        svg{
            height: 30px;
        }
        .flex.justify-between.flex-1{
            display:none;
        }
        .topbar{
            position: fixed;
            width: 100%;
        }
        .page-breadcrumb{
            padding: 100px 20px 0 20px;
        }
        @media only screen and (max-width: 768px) {
            .row{
                display:block;
            }
            .col-6, .col-8, .col-sm-4, .col-sm-6{
                width:100%;
            }
        }
    </style>

</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css')}} -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="{{url('admin/dashboard')}}">
                        <span class="logo-text">
{{--                            <!-- Light Logo text --> <b style="font-size: 20px;">Home Revise</b>--}}
                            <img src="{{asset('images/logo/home_revise.jpeg')}}" class="light-logo" alt="homepage" />
                        </span>
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                @if(auth('admin')->check())
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-start me-auto">
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
{{--                        <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark"--}}
{{--                                href="javascript:void(0)"><i class="ti-search"></i></a>--}}
{{--                            <form class="app-search position-absolute">--}}
{{--                                <input type="text" class="form-control" placeholder="Search &amp; enter"> <a--}}
{{--                                    class="srh-btn"><i class="ti-close"></i></a>--}}
{{--                            </form>--}}
{{--                        </li>--}}
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-end">
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#">
                                <a class="nav-link dropdown-toggle text-white waves-effect waves-dark" href="#" onclick="confirmLogout()"><i class="ti-lock m-r-5 m-l-5"></i> Logout</a>
                            </a>

{{--                            <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">--}}
{{--                                <!-- <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i>--}}
{{--                                    My Profile</a>--}}
{{--                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet m-r-5 m-l-5"></i>--}}
{{--                                    My Balance</a> -->--}}
{{--                                <a class="dropdown-item" href="{{url('admin/logout')}}"><i class="ti-key m-r-5 m-l-5"></i>--}}
{{--                                    Logout</a>--}}
{{--                            </ul>--}}
                        </li>

                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
                @endif
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        @if(auth('admin')->check())
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
{{--                        <li class="sidebar-item p-15 m-t-10"><a href="{{url('admin/dashboard')}}"--}}
{{--                                class="btn d-block w-100 create-btn text-white no-block d-flex align-items-center"><i--}}
{{--                                    class="fa fa-plus-square"></i> <span class="hide-menu m-l-5">Dashboard</span> </a>--}}
{{--                        </li>--}}

                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                                     href="{{url('admin/dashboard')}}" aria-expanded="false"><i class="mdi mdi-layers"></i><span
                                    class="hide-menu">Dashboard</span></a></li>
                        <!-- User Profile-->
                        <li class="sidebar-item d-none">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/dashboard')}}" aria-expanded="false"><i class="mdi mdi-layers"></i><span
                                    class="hide-menu">Dashboard</span></a></li>
                        <!-- <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/user')}}" aria-expanded="false"><i
                                    class="mdi mdi-account-network"></i><span class="hide-menu">Users</span></a></li> -->
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/states')}}" aria-expanded="false"><i class="mdi mdi-web"></i><span
                                    class="hide-menu">States</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/boards')}}" aria-expanded="false"><i class="mdi mdi-city"></i><span
                                    class="hide-menu">Boards</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/mediums')}}" aria-expanded="false"><i class="mdi mdi-bulletin-board"></i><span
                                    class="hide-menu">Mediums</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/standards')}}" aria-expanded="false"><i class="mdi mdi-translate-variant"></i><span
                                    class="hide-menu">Standards</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/subjects')}}" aria-expanded="false"><i class="mdi mdi-book-multiple"></i><span
                                    class="hide-menu">Subjects</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/chapters')}}" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span
                                    class="hide-menu">Chapters</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/topics')}}" aria-expanded="false"><i class="mdi mdi-file"></i><span
                                    class="hide-menu">Topics</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/subtopics')}}" aria-expanded="false"><i class="mdi mdi-newspaper-variant"></i><span
                                    class="hide-menu">SubTopics</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/questions')}}" aria-expanded="false"><i class="mdi mdi-file-document-box"></i><span
                                    class="hide-menu">Questions</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/quizes')}}" aria-expanded="false"><i class="mdi mdi-file-document"></i><span
                                    class="hide-menu">Quiz</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/courses')}}" aria-expanded="false"><i class="mdi mdi-bag-personal"></i><span
                                    class="hide-menu">Courses </span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/users')}}" aria-expanded="false"><i class="mdi mdi-account"></i><span
                                    class="hide-menu">Users</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/setting')}}" aria-expanded="false"><i class="mdi mdi-cog"></i><span
                                    class="hide-menu">Setting</span></a></li>

                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/gallery')}}" aria-expanded="false"><i class="mdi mdi-folder-image"></i><span
                                    class="hide-menu">Gallery</span></a></li>
                                    
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{url('admin/tools')}}" aria-expanded="false"><i class="mdi mdi-cogs"></i><span
                                    class="hide-menu">Tools</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" onclick="return confirmLogout()" aria-expanded="false"><i class="mdi mdi-lock"></i><span
                                    class="hide-menu">Logout</span></a></li>
                        <li class="sidebar-item">
                            <!-- <div class="user-content hide-menu m-l-10"> -->
                                <!-- <a href="#" class="sidebar-link waves-effect waves-dark sidebar-link" id="Userdd" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-download"></i><span class="hide-menu">Download App</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="Userdd">
                                    <a class="dropdown-item" href="{{url('/')}}/storage/uploads/apk/homerevise.apk"><i
                                            class="m-r-5 m-l-5"></i> Android Apk</a>
                                    <a class="dropdown-item" href="{{url('/')}}/storage/uploads/apk/homerevise_win.apk"><i
                                            class="m-r-5 m-l-5"></i> Window Apk</a>
                                </div> -->
                            <!-- </div> -->
                        </li>
                    </ul>

                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        @endif
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        @yield('content')
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <div class="footer text-center">
        Design and developed by <a href="https://www.linkedin.com/in/itsvnp/" target="_blank">Team Vivekananda</a>.
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "10000"
            }
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif(Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });

        function confirmDelete(message) {
            return confirm(message);
        }

        let table = new DataTable('.my_table');

    </script>
    <script>
        function confirmLogout() {
            var isConfirmed = confirm("Are you sure you want to logout?");
            if (isConfirmed) {
                window.location.href = "{{url('admin/logout')}}"; // Redirect to the logout URL if confirmed
            }
            // If not confirmed, you can choose to do nothing or add additional handling
        }
    </script>

    <script src="{{asset('backend/assets/libs/jquery/dist/jquery.min.js')}}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('backend/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('backend/dist/js/app-style-switcher.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{asset('backend/dist/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset('backend/dist/js/sidebarmenu.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('backend/dist/js/custom.js')}}"></script>
    <!--This page JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
@yield('scripts')
</body>

</html>
