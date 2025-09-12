@extends('admin.layouts.main')

@section('title')
    Student Attendance
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Attendance</h3>
                        <div class="row mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.student_attendance.index') !!}"
                                   class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{!! route('academic.student_attendance.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">
                                    <div class="row mt-3">

                                        <div class="row mt-3">

                                            <div class="col-md-6">
                                                <label for="campus"><b>Campus *</b></label>
                                                <select class="form-control select2 branch_select"  name="branch_id">
                                                    @foreach($branches as $branch)
                                                        <option
                                                            value="{!! $branch->id !!}">{!! $branch->name !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="branches"><b>Class: *</b></label>
                                                <select name="class_id"
                                                        class="form-select select2 basic-single mt-3 select_class"
                                                        aria-label=".form-select-lg example" >

                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-4">

                                            <div class="col-md-4">
                                                <label for="branches"><b>Section: *</b></label>
                                                <select required name="section_id"
                                                        class="form-select select2 basic-single mt-3 select_section"
                                                        aria-label=".form-select-lg example">
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="branches"><b>Date: *</b></label>
                                                <input type="date" required class="form-control" max={{ date('Y-m-d') }} name="attendance_date">
                                            </div>

                                        </div>

                                        <div class="clearfix"></div>

                                        <div class="panel-body pad table-responsive">
                                            <div id="loadData"></div>


                                            <div style="margin-top: 20px">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')

    <link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">

@endsection
@section('js')

    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>



    <script>
        $(document).ready(function () {

            $("#form_validation").validate({
                errorPlacement: function (error, element) {
                    error.addClass("text-danger small");

                    if (element.hasClass("select2-hidden-accessible")) {
                        error.insertAfter(element.next(".select2-container"));
                    } else if (element.closest(".input-group").length) {
                        error.insertAfter(element.closest(".input-group"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                rules: {
                    branch_id: {
                        required: true
                    },
                    class_id: {
                        required: true
                    },
                    section_id: {
                        required: true
                    },
                    attendance_date: {
                        required: true
                    }
                },
                messages: {
                    branch_id: {
                        required: "Please select campus"
                    },
                    class_id: {
                        required: "Please select class"
                    },
                    section_id: {
                        required: "Please select section"
                    },
                    attendance_date: {
                        required: "Please select date"
                    }
                }
            });

            $('.branch_select').on('change', function () {

                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_class').empty();
                        sectionDropdown.append('<option value="" disabled selected>Select Class</option>');
                        data.forEach(function (academic_class) {
                            sectionDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        })
    </script>


    <script>
        $(document).ready(function () {
            $('.select_class').on('change', function () {

                var class_id = $('.select_class').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSections') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_section').empty();
                        sectionDropdown.append('<option value="">Select Section</option>');

                        data.forEach(function (section) {
                            sectionDropdown.append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            });
        })
    </script>

    <script>
        $(document).ready(function () {
            function fetch_student() {
                var formData = $('#form_validation').serialize();
                // formData += '&page=' + page;
                var loader = $('<div class="loader"></div>').appendTo('body');

                $.ajax({
                    url: "{{ route('academic.student.data') }}",
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        loader.remove();
                        $('#loadData').html(data);
                    },
                    error: function (request, error) {
                        loader.remove();
                        console.log("Request: " + JSON.stringify(request));
                    }
                });
            }

            $('.select_section').on('change', function () {
                fetch_student();
            });

            // $(document).on('click', '.pagination a', function (event) {
            //     event.preventDefault();
            //     var page = $(this).attr('href').split('page=')[1];
            //     fetch_fee(page);
            // });
        });

    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector("#form_validation");

            form.addEventListener("submit", function (event) {
                const presentChecked = document.querySelector('.present-radio:checked');
                const absentChecked = document.querySelector('.absent-radio:checked');
                const leaveChecked = document.querySelector('.leave-radio:checked');

                if (!presentChecked && !absentChecked) {
                    event.preventDefault();
                    toastr.warning("Please select either Present,Leave or Absent.");
                }
            });
        });
    </script>


@endsection

