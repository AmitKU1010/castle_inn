<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('admin-lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- <link rel="stylesheet" href="{{ asset('admin-lte/plugins/datatables/jquery.dataTables.min.css') }}"> -->
  <link rel="stylesheet" href="{{ asset('admin-lte/plugins/datatables/dataTables.bootstrap4.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('admin-lte/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-lte/plugins/sweetalert2/sweetalert2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-lte/plugins/toastr/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      @auth
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="{{ asset('admin-lte/dist/img/user2-160x160.jpg') }}" class="user-image img-circle elevation-2" alt="User Image">
          <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="{{ asset('admin-lte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            <p>
              {{ Auth::user()->name }} - {{ Auth::user()->roles->first()->name }}
              <small>{{ Auth::user()->created_at->format('jS F Y h:i:s A') }}</small>
            </p>
          </li>

          <li class="user-footer">
            <a href="#" class="btn btn-default btn-flat">Change Password</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-default btn-flat float-right">
              {{ __('Logout') }}
            </a>
          </li>
        </ul>
      </li>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
      @endauth

      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{ asset('admin-lte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">
        {{ config('app.name', 'Laravel') }}
      </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          </li>

           @php

         $auth_id=Auth::user()->id;
         if($auth_id=='3')
         {
            @endphp
         
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Master Forms
                <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">6</span>
              </p>
            </a>


            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('stock_supplier') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Supplier</p>
                </a>
              </li>
 

              <li class="nav-item">
                <a href="{{route('item_catagories')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Product Brand/Drink Type</p>
                </a>
              </li>


              <li class="nav-item">
                <a href="{{route('item_name')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Product Name</p>
                </a>
              </li>
            </ul>
          </li>

           <li class="nav-item has-treeview menu-open">
            <li class="nav-item">
            <a href="{{route('purchase')}}" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Purchase
              </p>
            </a>
          </li>
          </li>


          <li class="nav-item has-treeview">
            <li class="nav-item">
            <a href="{{route('issue')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Issue Product
              </p>
            </a>
          </li>
          </li>


             <li class="nav-item has-treeview">
             <li class="nav-item">
             <a href="{{route('central_stock')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Centra Stock
              </p>
            </a>
            </li>
            </li>


            @php
         }
         else
         {
        @endphp
           
            
             <!--  <li class="nav-item has-treeview">
             <li class="nav-item">
             <a href="{{route('available_unit_stock')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Available Stock
              </p>
            </a>
            </li>
            </li> -->

        <!--      <li class="nav-item has-treeview">
             <li class="nav-item">
             <a href="{{route('use_product')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Use Product
              </p>
            </a>
            </li>
            </li> -->
<!-- 
             <li class="nav-item has-treeview">
             <li class="nav-item">
             <a href="{{route('available_unit_stock')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Available Stock
              </p>
            </a>
            </li>
            </li> -->

            <!--  <li class="nav-item has-treeview">
             <li class="nav-item">
             <a href="{{route('item_types_kot')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Manage Drink Types
              </p>
            </a>
            </li>
            </li>


             <li class="nav-item has-treeview">
             <li class="nav-item">
             <a href="{{route('items_kot')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Manage Drinks
              </p>
            </a>
            </li>
            </li>
 -->

             <li class="nav-item has-treeview">
             <li class="nav-item">
             <a href="{{route('counter_sale')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Counter Sale
              </p>
            </a>
            </li>
            </li>

             <li class="nav-item has-treeview">
             <li class="nav-item">
             <a href="{{route('available_unit_stock')}}" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
               Available Stock
              </p>
            </a>
            </li>
            </li>
            
           @php
         }  
           @endphp

        </ul>
      </nav>
 
      
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    @yield('content')

  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      {{ config('app.name', 'Laravel') }}
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2019 <a href="#">Phoneix Software Solutions</a>.</strong>
  </footer>
</div>

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->



<!-- jQuery -->
<script src="{{ asset('admin-lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin-lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin-lte/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin-lte/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin-lte/plugins/toastr/toastr.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin-lte/dist/js/adminlte.min.js') }}"></script>

@stack('scripts')

</body>
</html>
