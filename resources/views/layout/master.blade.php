<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Netlink Inventory</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link type="text/css" href="{{url('css/app.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{url('css/custom.css')}}">
    <!-- end of global css -->
    <!--pagelevel css-->
    <link rel="stylesheet" href="{{url('vendors/morrisjs/css/morris.css')}}">
    <link rel="stylesheet" href="{{url('css/pages/dashboard1.css')}}">
    <script src="{{url('js/app.js')}}" type="text/javascript"></script>
    <script type="text/javascript" src="{{url('angular/bower_components/angular/angular.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::to('angular/ng-img-crop.js')}}"></script>
    <script type="text/javascript" src="{{url('angular/bower_components/ng-file-upload/ng-file-upload-shim.min.js')}}"></script>
    <script type="text/javascript" src="{{url('angular/bower_components/ng-file-upload/ng-file-upload.min.js')}}"></script>
    <script type="text/javascript" src="{{url('angular/bower_components/angularUtils-pagination/dirPagination.js')}}"></script>
    
    <script type="text/javascript" src="{{url('angular/app.js')}}"></script>
    
    <!--end of pagelevel css-->
</head>

<body ng-app="masterApp">
<!-- header logo: style can be found in header-->
<header class="header">
    <nav class="navbar navbar-static-top" role="navigation">
        @section('header')
            @include('layout.header')
        @show
    </nav>
</header>
<div class="wrapper">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-aside">
        <!-- sidebar: style can be found in sidebar-->
        <section class="sidebar">
            @section('sidebar')
                @include('layout.sidebar')
            @show
        </section>
        <!-- /.sidebar -->
    </aside>
    <aside class="right-aside">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>@yield('title')</h1>
        </section>
        <!-- Main content -->
        <section class="content" style="min-height: 500px">
            @yield('content')
        </section>
        <!-- /.content -->
    </aside>
    <!-- /.right-side -->
</div>
<!-- ./wrapper -->
<!-- global js -->

<!--morris chart-->
<script src="{{url('vendors/raphael/raphael.min.js')}}" type="text/javascript"></script>
<script src="{{url('vendors/morrisjs/js/morris.min.js')}}" type="text/javascript"></script>
<script src="{{url('js/plugins/jquery.flot.spline.js')}}" type="text/javascript"></script>
<script src="{{url('js/pages/dashboard1.js')}}" type="text/javascript"></script>
<!-- end of page level js -->
</body>

</html>
