<!-- main-header -->
<div class="main-header side-header sticky nav nav-item">
    <div class="container-fluid main-container ">
        <div class="main-header-left ">
            <div class="responsive-logo">
                <a href="index.html" class="header-logo">
                    <img src="../assets/img/brand/logo.png" class="logo-1" alt="logo">

                    <img src="../assets/img/brand/logo.png" class="dark-logo-1" alt="logo">
                </a>
            </div>
            <div class="app-sidebar__toggle" data-bs-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
            {{-- <div class="main-header-center ms-3 d-sm-none d-md-none d-lg-block">
                <input class="form-control" placeholder="Search for anything..." type="search">
                <button class="btn"><i class="fas fa-search d-none d-md-block"></i></button>
            </div> --}}
        </div>
        <div class="main-header-right">
            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon fe fe-more-vertical "></span>
            </button>
            <div class="mb-0 navbar navbar-expand-lg navbar-nav-right responsive-navbar navbar-dark p-0">
                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                    <ul class="nav nav-item  navbar-nav-right ms-auto">

                        {{-- Notifications --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="javascript:void(0);" id="notification" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell" style="margin-top:0.7rem; font-size: 1.1rem;"></i>
                                <span id="countNotification" class="countNotification badge bg-danger position-absolute top-10 start-90 translate-middle"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" id="notificationDropdown" style="top: 51px; left: -225px; width: 400px; height: 400px; overflow-y: scroll;">
                                <li><h6 class="dropdown-header text-center fs-5">Notifications</h6></li>

                            </ul>
                        </li>

                        <li class="dropdown nav-item main-layout">
                            <a class="new nav-link theme-layout nav-link-bg layout-setting">
                                <span class="dark-layout"><svg xmlns="http://www.w3.org/2000/svg"
                                        class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24">
                                        <path
                                            d="M20.742 13.045a8.088 8.088 0 0 1-2.077.271c-2.135 0-4.14-.83-5.646-2.336a8.025 8.025 0 0 1-2.064-7.723A1 1 0 0 0 9.73 2.034a10.014 10.014 0 0 0-4.489 2.582c-3.898 3.898-3.898 10.243 0 14.143a9.937 9.937 0 0 0 7.072 2.93 9.93 9.93 0 0 0 7.07-2.929 10.007 10.007 0 0 0 2.583-4.491 1.001 1.001 0 0 0-1.224-1.224zm-2.772 4.301a7.947 7.947 0 0 1-5.656 2.343 7.953 7.953 0 0 1-5.658-2.344c-3.118-3.119-3.118-8.195 0-11.314a7.923 7.923 0 0 1 2.06-1.483 10.027 10.027 0 0 0 2.89 7.848 9.972 9.972 0 0 0 7.848 2.891 8.036 8.036 0 0 1-1.484 2.059z" />
                                    </svg></span>
                                <span class="light-layout"><svg xmlns="http://www.w3.org/2000/svg"
                                        class="header-icon-svgs" width="24" height="24" viewBox="0 0 24 24">
                                        <path
                                            d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z" />
                                    </svg></span>
                            </a>
                        </li>
                        <li class="nav-link search-icon d-lg-none d-block">
                            <form class="navbar-form" role="search">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search">
                                    <span class="input-group-btn">
                                        <button type="reset" class="btn btn-default">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button type="submit" class="btn btn-default nav-link resp-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                class="header-icon-svgs" viewBox="0 0 24 24" width="24px"
                                                fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                                            </svg>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </li>
                        <li class="nav-item full-screen fullscreen-button">
                            <a class="new nav-link full-screen-link" href="javascript:void(0);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-maximize">
                                    <path
                                        d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                                    </path>
                                </svg>
                            </a>
                        </li>
                        <li class="dropdown main-profile-menu nav nav-item nav-link">
                            <a class="profile-user d-flex" href=""><img alt=""
                                    src="{!! URL::asset('dist/assets/img/faces/6.png') !!}"></a>
                            <div class="dropdown-menu">
                                {{-- <div class="main-header-profile bg-primary p-3">--}}
                                    {{-- <div class="d-flex wd-100p">--}}
                                        {{-- <div class="main-img-user"><img alt="" --}} {{--
                                                src="{!! URL::asset('dist/assets/img/faces/6.jpg') !!}" --}} {{--
                                                class=""></div>--}}
                                        {{-- <div class="ms-3 my-auto">--}}
                                            {{-- {{\Illuminate\Support\Facades\Auth::user()->name}}--}}
                                            {{-- <h6>Petey Cruiser</h6><span>Premium Member</span>--}}
                                            {{-- </div>--}}
                                        {{-- </div>--}}
                                    {{-- </div>--}}
                                {{-- <a class="dropdown-item" href=""><i class="bx bx-user-circle"></i>Profile</a>--}}
                                {{-- <a class="dropdown-item" href=""><i class="bx bx-cog"></i> Edit Profile</a>--}}
                                {{-- <a class="dropdown-item" href=""><i class="bx bxs-inbox"></i>Inbox</a>--}}
                                {{-- <a class="dropdown-item" href=""><i class="bx bx-envelope"></i>Messages</a>--}}
                                {{-- <a class="dropdown-item" href=""><i class="bx bx-slider-alt"></i> Account
                                    Settings</a>--}}

                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    <i class="bx bx-log-out"></i> Sign Out
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        {{-- <li class="dropdown main-header-message right-toggle">--}}
                            {{-- <a class="nav-link pe-0" data-bs-toggle="sidebar-right"
                                data-bs-target=".sidebar-right">--}}
                                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs"
                                    viewBox="0 0 24 24" --}} {{-- fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" --}} {{-- stroke-linejoin="round"
                                    class="feather feather-menu">--}}
                                    {{-- <line x1="3" y1="12" x2="21" y2="12"></line>--}}
                                    {{-- <line x1="3" y1="6" x2="21" y2="6"></line>--}}
                                    {{-- <line x1="3" y1="18" x2="21" y2="18"></line>--}}
                                    {{-- </svg>--}}
                                {{-- </a>--}}
                            {{-- </li>--}}
                    </ul>
                </div>
            </div>
            {{-- <div class="d-flex">
                <a class="demo-icon new nav-link" href="javascript:void(0);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs fa-spin" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path
                            d="M12 16c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.084 0 2 .916 2 2s-.916 2-2 2-2-.916-2-2 .916-2 2-2z" />
                        <path
                            d="m2.845 16.136 1 1.73c.531.917 1.809 1.261 2.73.73l.529-.306A8.1 8.1 0 0 0 9 19.402V20c0 1.103.897 2 2 2h2c1.103 0 2-.897 2-2v-.598a8.132 8.132 0 0 0 1.896-1.111l.529.306c.923.53 2.198.188 2.731-.731l.999-1.729a2.001 2.001 0 0 0-.731-2.732l-.505-.292a7.718 7.718 0 0 0 0-2.224l.505-.292a2.002 2.002 0 0 0 .731-2.732l-.999-1.729c-.531-.92-1.808-1.265-2.731-.732l-.529.306A8.1 8.1 0 0 0 15 4.598V4c0-1.103-.897-2-2-2h-2c-1.103 0-2 .897-2 2v.598a8.132 8.132 0 0 0-1.896 1.111l-.529-.306c-.924-.531-2.2-.187-2.731.732l-.999 1.729a2.001 2.001 0 0 0 .731 2.732l.505.292a7.683 7.683 0 0 0 0 2.223l-.505.292a2.003 2.003 0 0 0-.731 2.733zm3.326-2.758A5.703 5.703 0 0 1 6 12c0-.462.058-.926.17-1.378a.999.999 0 0 0-.47-1.108l-1.123-.65.998-1.729 1.145.662a.997.997 0 0 0 1.188-.142 6.071 6.071 0 0 1 2.384-1.399A1 1 0 0 0 11 5.3V4h2v1.3a1 1 0 0 0 .708.956 6.083 6.083 0 0 1 2.384 1.399.999.999 0 0 0 1.188.142l1.144-.661 1 1.729-1.124.649a1 1 0 0 0-.47 1.108c.112.452.17.916.17 1.378 0 .461-.058.925-.171 1.378a1 1 0 0 0 .471 1.108l1.123.649-.998 1.729-1.145-.661a.996.996 0 0 0-1.188.142 6.071 6.071 0 0 1-2.384 1.399A1 1 0 0 0 13 18.7l.002 1.3H11v-1.3a1 1 0 0 0-.708-.956 6.083 6.083 0 0 1-2.384-1.399.992.992 0 0 0-1.188-.141l-1.144.662-1-1.729 1.124-.651a1 1 0 0 0 .471-1.108z" />
                    </svg>
                </a>
            </div> --}}
        </div>
    </div>
</div>
<!-- /main-header -->
