<!DOCTYPE html>
<html>

<head>
    @include('partials.head')

</head>

<body class="main-body app sidebar-mini">
    <div id="app">
        <div class="wrapper">

            @include('partials.topbar')
            @include('partials.sidebar')
            <div class="content-wrapper">
                <section class="content">
                    <h3 class="page-title">
                        {{ $siteTitle ?? '' }}
                    </h3>

                    <div class="row">
                        @yield('breadcrumbs')
                        <div class="col-md-12">
                            @if (Session::has('message'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                <p>{{ Session::get('message') }}</p>
                            </div>
                            @endif
                            @if ($errors->count() > 0)
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-warning"></i> Something went wrong!</h4>
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-12">
                            @yield('content')
                        </div>
                    </div>
                </section>
            </div>

            @include('partials.footer')

            <div class="control-sidebar-bg"></div>
        </div>
    </div>
    @include('partials.javascripts')

    <script type="text/javascript">
        $(document).ready(function(){
            $('#datatable').DataTable()
        });

    </script>


</body>

</html>