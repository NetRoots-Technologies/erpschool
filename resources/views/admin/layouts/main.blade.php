<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <title>@yield('title')</title>
    @include('admin.layouts.css')
    @yield('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body class="main-body app sidebar-mini ltr">
    <div class="page custom-index">
        <div>
            @include('admin.layouts.header')
            @include('admin.layouts.sidebar')
            {{-- @include('admin.layouts.switcher')--}}
        </div>

        <div class="main-content app-content">
            <div class="main-container container-fluid">
                @include('admin.layouts.breadcrumb')
                {{-- @if(session('error'))--}}
                {{-- <div class="alert alert-danger">--}}
                {{-- {{ session('error') }}--}}
                {{-- </div>--}}
                {{-- @endif--}}


                <div class="container">
                    <div class="row">
                        <div class="col-8 offset-1">
                            {{-- @if(Session::has('success'))--}}
                            {{-- <div class="alert alert-success alert-dismissible fade show" role="alert">--}}
                            {{-- {{Session::get('success') }}--}}
                            {{-- <button type="button" class="close" data-bs-dismiss="alert" --}} {{--
                                    aria-label="Close">--}}
                            {{-- <span aria-hidden="true">&times;</span>--}}
                            {{-- </button>--}}
                            {{-- </div>--}}
                            {{-- @endif--}}

                            {{-- @if (Session::has('danger'))--}}
                            {{-- <div class="alert alert-danger alert-dismissible fade show" role="alert">--}}
                            {{-- {{Session::get('danger') }}--}}
                            {{-- <button type="button" class="close" data-bs-dismiss="alert" --}} {{--
                                    aria-label="Close">--}}
                            {{-- <span aria-hidden="true">&times;</span>--}}
                            {{-- </button>--}}
                            {{-- </div>--}}
                            {{-- @endif--}}
                        </div>
                    </div>
                </div>
                @yield('content')
                <a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>
            </div>


        </div>
        <div class="modal fade bd-example-modal-lg" id="paymentdelay">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Payment over due </h4>
                        <button type="button" id="close" class="close modalclose" data-dismiss="modal">&times;
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body" id="modal-body-create">
                        <div class="text-center">
                            <img class="imgclose" src="{!! asset('dist/overdue.png') !!}" class="img-fluid" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.layouts.footer')
        <script>
            // $(document).ready(function() {
            // $('.basic-single').select2();
            // });

        </script>
    </div>


    @include('admin.layouts.js')
    @yield('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"
        integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        const read = @json(route('admin.notification'));
        const unread = @json(route('admin.getUnreadCount'));

        $.ajax({
            url: unread,
            type: 'GET',
            beforeSend: function (xhr) {
                var token = $('meta[name="csrf-token"]').attr('content');
                xhr.setRequestHeader('X-CSRF-TOKEN', token);
            },
            success: function(res) {
                $("#countNotification").text(res.unread.count);

                if(res.unread.count === 0) {
                    $("#countNotification").hide();
                }
                // data.notifications.forEach(element => {
                //     console.log(element.title);
                // });
            }
        })

        $(document).on('click', '#notification', function (e) {
          
            e.preventDefault();
           $("#countNotification").text("");


            $.ajax({
                url: read,
                type: 'GET',
                beforeSend: function (xhr) {
                    var token = $('meta[name="csrf-token"]').attr('content');
                    xhr.setRequestHeader('X-CSRF-TOKEN', token);
                },
                success: function (res) {
                    console.log("🚀 ~ dataddaadadadad>>", res.data.notifications);
                    let notificationsHtml = '';
                    res.data.notifications.forEach(element => {
                        notificationsHtml += `
                            <li>
                                <div class="dropdown-item" style="font-size:1rem !important;">
                                    <b>${element.title}</b><br>
                                    <small class="text-muted" style="font-size:0.8rem;">${element.message}</small>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-success approve-btn" data-id="${element.id}">Approve</button>
                                        <button class="btn btn-sm btn-warning text-white pending-btn" data-id="${element.id}">Reject</button>
                                    </div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        `;
                    });
                    $("#notificationDropdown").html(notificationsHtml);
                }

            })
        });


        $('#notificationDropdown').on('click', '.approve-btn', function () {
            const id = $(this).data('id');
            $.ajax({
                url: '{{ route("admin.notifications.approve") }}',
                method: 'POST',
                data: { id },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    toastr.success('Request approved.');
                    $('#notification').click();
                },
                error: function () {
                    toastr.error('Failed to approve.');
                }
            });
        });

        $('#notificationDropdown').on('click', '.pending-btn', function () {
            const id = $(this).data('id');

            $.ajax({
                url: '{{ route("admin.notifications.pending") }}',
                method: 'POST',
                data: { id },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function () {
                    toastr.info('Request marked as pending.');
                    $('#notification').click();
                },
                error: function () {
                    toastr.error('Failed to mark as pending.');
                }
            });
        });



        $(document).ready(function () {
            let successMessage = @json(session('success'));
            let errorMessage = @json(session('error'));

            if (successMessage) {
                toastr.success(successMessage);
            }

            if (errorMessage) {
                toastr.error(errorMessage);
            }
        });

        $(".modalclose").click(function () {

            $('#myModal').modal('hide');
            $('.modal').modal('hide');
            $(".modal-backdrop").remove();
        });

        $(".modalclose").click(function () {

            $('.modal').modal('hide');
        });
        $(".imgclose").click(function () {

            $('.modal').modal('hide');
        });

        var delayInMilliseconds = 180000; //10 minute
        // var delayInMilliseconds = 5000; //10 minute

        // setTimeout(function () {
        //     $('#paymentdelay').modal('show');
        //     document.getElementById('logout-form').submit();
        //
        // }, delayInMilliseconds);

        $(document).ready(function () {

            // $('#paymentdelay').modal('show');

        });

         $(document).ready(function () {
            $('.coa-toggle').on('click', function (e) {
                e.preventDefault();
                const $submenu = $(this).next('.coa-submenu');
                $submenu.slideToggle(200);
            });

            // Automatically open if active
            if ($('.coa-submenu .active').length) {
                $('.coa-submenu').show();
            }
        });

    </script>
</body>

</html>
