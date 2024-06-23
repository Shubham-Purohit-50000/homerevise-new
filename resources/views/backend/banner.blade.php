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
                <h4 class="page-title">Sponsor</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Sponsor</a></li>
                            <li class="breadcrumb-item active" aria-current="page">list</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7">
                <div class="text-end upgrade-btn">
                    <a href="{{url('admin/create/sponsor')}}" class="btn btn-danger text-white">+ Create Sponsor</a>
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
                            <h4 class="card-title">All Sponsors</h4>
                            <h5 class="card-subtitle">Overview of all sponsor</h5>
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
                                <th class="border-top-0" width="20px">Sponsor ID</th>
                                <th class="border-top-0" width="200px">Image</th>
                                <th class="border-top-0">Sponsor</th>
                                <th class="border-top-0">Data</th>
                                <th class="border-top-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (filled($banners))
                                @foreach ($banners as $item)
                                <tr>
                                <td>#{{$item->id}}</td>
                                <td><img src="{{ asset('storage/' . $item->image) }}" class="w-100"></td>
                                <td>{{ $item->sponsor ?? 'Nil' }}</td>
                                <td>{{ $item->data ?? 'Nil' }}</td>
                                <td>
                                    <a href="{{url('admin/delete/sponsor/' . $item->id)}}" style="font-size:2rem" class="text-danger mdi mdi-delete-circle"></a>
                                </td>
                                </tr>
                                @endforeach
                            @else
                                <h5 class="text-primary text-center">No Sponsor</h5>
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