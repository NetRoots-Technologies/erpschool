<head>
    <style>
        .active {
            color: black !important;
        }
    </style>
</head>

@inject('request', 'Illuminate\Http\Request')


<div id="global-loader">
    <img src="{{ url('public/theme') }}/assets/img/loaders/loader-4.svg" class="loader-img" alt="Loader">
</div>

<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="main-sidebar app-sidebar sidebar-scroll">
    <div class="main-sidebar-header" style="height: 100px; ">
        <a class="desktop-logo logo-light" href="#"
            style="margin-top: -35px !important; margin-left: 0px !important;text-align: center;font-size: 17px;margin: 0px;font-weight: 500;"
            class="text-center mx-auto">
            <img src="{{ URL::asset('public/images/15.jpg') }}" style="width: 185px;">
            {{-- <img src="{{ URL::asset('public/images/logo.png') }}" style="margin-top:5px;">--}}
        </a>
    </div>
    <div class="main-sidebar-loggedin" style="margin-top:100px;">
        <div class="app-sidebar__user">
            <div class="dropdown user-pro-body text-center">
                <div class="user-pic">
                    <img src="{{ URL::asset('public/images/user-icon.png') }} " style="margin-top:5px;" alt="user-img"
                        class="rounded-circle mCS_img_loaded" style="width: 60px;height: 60px;font-size: 36px;">

                </div>
                <div class="user-info">
                    <h6 class=" mb-0 text-dark" style="color:white;">{{Auth::user()->name}}</h6>
                    <span class="text-muted app-sidebar__user-name text-sm" style="color:white;">
                        @if(isset(Auth::user()->role)) {{Auth::user()->role->name}}
                        @endif </span>
                </div>
            </div>
        </div>
    </div><!-- /user -->
    <div class="sidebar-navs">
        <ul class="nav  nav-pills-circle">
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Settings"
                aria-describedby="tooltip365540">
                <a class="nav-link text-center m-2" href="#">
                    <i class="fe fe-settings"></i>
                </a>
            </li>
            @if(isset(Auth::user()->role))
            @if(!Auth::user()->hasRole('admin'))
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Profile">
                <a class="nav-link text-center m-2" href="#">
                    <i class="fe fe-user"></i>
                </a>
            </li>
            @endif
            @endif
            <li class="nav-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Logout">

                <a href="#logout" onclick="$('#logout').submit();" class="nav-link text-center m-2">
                    <i class="fe fe-power"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="main-sidebar-body">
        <ul class="side-menu ">



            <li class="slide">
                <a class="side-menu__item" href="#"><i class="side-menu__icon fe fe-airplay"></i><span
                        class="side-menu__label">Dashboards</span></a>
            </li>

            <!-- Roles And Permission -->
            {{-- @can('erp_roles_manage',$per)--}}

            <li
                class="slide {{ ($request->segment(2) == 'roles' || $request->segment(1) == 'role' ||$request->segment(2) == 'candidate-hire') ?  : '' }}">
                <a href="#" data-toggle="slide" class="side-menu__item">
                    <i class="side-menu__icon fa fa-calendar"></i>
                    <span class="side-menu__label">Roles And Permission</span>
                </a>


                <ul class="slide-menu">
                    <li class="{{ $request->segment(2) == 'roles' ? 'active active-sub' : '' }}">
                        <a class="sub-side-menu__item" href="#">

                            <span class="title">ERP</span>
                        </a>
                    </li>
                    <li class="{{ $request->segment(1) == 'role' ? 'active active-sub' : '' }}">
                        <a class="sub-side-menu__item" href="#">

                            <span class="title">Academics</span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- @endcan--}}

            <!-- Roles And Permission -->

    </div>
</aside>