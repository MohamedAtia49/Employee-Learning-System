@inject('users', 'App\Models\User')

@include('employee.partials.header')
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
@include('employee.partials.navbar')

@include('employee.partials.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1> @yield('page_title')
                <small> @yield('small_title') </small>
            </h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('/employee/home') }}">Home</a></li>
                <li class="breadcrumb-item active">Employee Learning System Dashboard</li>
            </ol>
            </div>
        </div>
        </div>
    <!-- /.container-fluid -->
    </section>

        @yield('content')

    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

@include('employee.partials.footer')
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@include('employee.partials.scripts')

</body>
</html>
