@extends('layouts.app')
@inject('client' ,'App\Models\Client')
@inject('restaurant' ,'App\Models\Restaurant')
@inject('city' ,'App\Models\City')
@inject('category' ,'App\Models\Category')
@inject('city' ,'App\Models\City')

@section('page_title')
    Dashboard
@endsection
@section('small_title')
    Statisttics
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">

        <div class="card">
            <div class="card-header">
            <h3 class="card-title">Welcome</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
                </button>
            </div>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                    </div>
                @endif
                        'You are logged in!'
                        <div class="row mt-5">
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="info-box">
                                <span class="info-box-icon bg-dark"><i class="far fa-user"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">My Courses</span>
                                    <span class="info-box-number">
                                        {{-- {{ $restaurant->count() }} --}}
                                    </span>
                                </div>
                                <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        </div>
                        <!-- Default box -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
@endsection
