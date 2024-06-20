@extends('backend.layout')
@section('title','Banner')
@section('content')

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row align-items-center">
            <div class="col-5">
                <h4 class="page-title">Banner</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Banner</a></li>
                            <li class="breadcrumb-item active" aria-current="page">list</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/create/banner')}}" class="btn btn-danger text-white">+ Create Banner</a>
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
                    <div class="d-md-flex">
                        <div>
                            <h4 class="card-title">All Banner</h4>
                            <h5 class="card-subtitle">Overview of all banner</h5>
                        </div>
                        <div class="ms-auto d-none">
                            <div class="dl">
                            <select class="form-select shadow-none">
                                <option value="0" selected>Monthly</option>
                                <option value="1">Daily</option>
                                <option value="2">Weekly</option>
                                <option value="3">Yearly</option>
                            </select>
                            </div>
                        </div>
                    </div>
                    <!-- title -->
                </div>
                <div class="table-responsive">
                    <table class="table v-middle">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-top-0">Banner ID</th>
                                <th class="border-top-0" width="200px">Banner</th>
                                <th class="border-top-0" width="200px">State</th>
                                <th class="border-top-0" width="200px">Data</th>
                                <th class="border-top-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $btn_colors = array('info','orange','success','purple','danger');
                            @endphp
                            @if (filled($banners))
                                @foreach ($banners as $item)
                                @php
                                    $rand = rand(0,4);
                                    $btn_color = $btn_colors[$rand];
                                @endphp
                                <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="m-r-10">
                                            <a class="btn btn-circle d-flex btn-{{$btn_color}} text-white uppercase-text">
                                                #{{$item->id}}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td><img src="{{ asset('storage/' . $item->image) }}" class="w-100"></td>
                                <td>{{ $item->state ?? 'Nil' }}</td>
                                <td>{{ $item->data ?? 'Nil' }}</td>
                                <td>
                                    <a href="{{url('admin/delete/banner/' . $item->id)}}" style="font-size:2rem" class="text-danger mdi mdi-delete-circle"></a>
                                </td>
                                </tr>
                                @endforeach
                            @else
                                <h5 class="text-primary text-center">No Banner</h5>
                            @endif
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>
@endsection