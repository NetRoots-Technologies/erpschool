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
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Attendance</h3>
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
                        <form action="{!! route('academic.student_attendance.update',$studentAttendance->id) !!}"
                              enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            {{method_field('put')}}
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">
                                    <div class="row mt-3">

                                        <div class="row mt-3">

                                            <div class="col-md-6">
                                                <label for="campus"><b>Campus *</b></label>
                                                <select class="form-control select2 branch_select" name="branch_id">
                                                    @foreach($branches as $branch)
                                                        <option
                                                            value="{!! $branch->id !!}" {!! $studentAttendance->branch_id == $branch->id ? 'selected' : '' !!}>{!! $branch->name !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="branches"><b>Class: *</b></label>
                                                <select required name="class_id"
                                                        class="form-select select2 basic-single mt-3 select_class"
                                                        aria-label=".form-select-lg example">
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
                                                <input type="date" class="form-control"
                                                       value="{!! $studentAttendance->attendance_date !!}"
                                                       name="attendance_date" max="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>

                                        <div class="panel-body pad table-responsive">
                                            <table id="users-table"
                                                   class="table radio_table table-bordered table-striped datatable mt-3"
                                                   style="width: 100%">
                                                <thead>
                                                <tr>
                                                    <th>Sr.#</th>
                                                    <th>Student Name</th>
                                                    <th class="header-radio">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input present-radio radio_button_style"
                                                                   id="check-all-present" name="check-all" type="radio">
                                                            <label class="form-check-label" for="check-all-present">Present</label>
                                                        </div>
                                                    </th>
                                                    <th class="header-radio">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input absent-radio radio_button_style"
                                                                   id="check-all-absent" name="check-all" type="radio">
                                                            <label class="form-check-label" for="check-all-absent">Absent</label>
                                                        </div>
                                                    </th>
                                                    <th class="header-radio">
                                                        <div class="form-check form-check-inline">
                                                            <input  class="form-check-input leave-radio radio_button_style"
                                                                    id="check-all-leave" name="check-all" type="radio">
                                                            <label class="form-check-label" for="check-all-leave">Leave</label>
                                                        </div>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($studentAttendance->AttendanceData as $attendance)
                                                    <tr>
                                                        <td class="col-2">{!! $loop->iteration !!}</td>
                                                        <td class="col-5">
                                                            <div class="d-flex align-items-center">

                                                                <input type="hidden" name="student_id[]"
                                                                       value="{!! $attendance->student->id !!}">
                                                                <input type="text" class="form-control"
                                                                       name="student_name[]"
                                                                       value="{!! $attendance->student->student_id.' - '.$attendance->student->first_name.' '.$attendance->student->last_name !!}" readonly>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-check-inline">

                                                                <input  class="form-check-input present-radio"
                                                                       type="radio"
                                                                       name="attendance[{!! $attendance->student->id !!}]"
                                                                       id="present_{!! $attendance->student->id !!}"
                                                                       value="P"
                                                                       data-id="{!! $attendance->student->id !!}" {{ $attendance->attendance == 'P' ? 'checked' : '' }}>
                                                                Present
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="form-check form-check-inline">

                                                                <input class="form-check-input absent-radio"
                                                                       type="radio"
                                                                       name="attendance[{!! $attendance->student->id !!}]"
                                                                       id="absent_{!! $attendance->student->id !!}"
                                                                       value="A"
                                                                       data-id="{!! $attendance->student->id !!}" {{ $attendance->attendance == 'A' ? 'checked' : '' }}>
                                                                Absent
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-check-inline">
                                                                        <select class="form-control leave_attendence" name="attendance[{!! $attendance->student->id  !!}]"
                                                                            id="leave_{!! $attendance->student->id  !!}" data-id="{!! $attendance->student->id  !!}">
                                                                            <option value="" disabled selected>Select Leave Type</option>
                                                                            <option {{$attendance->attendance == "Death" ? "selected" : ""}} value="Death">Death</option>
                                                                            <option {{$attendance->attendance == "Discipline" ? "selected" : ""}} value="Discipline">Discipline</option>
                                                                            <option {{$attendance->attendance == "Event Leave" ? "selected" : ""}} value="Event Leave">Event Leave</option>
                                                                            <option {{$attendance->attendance == "Long Leave" ? "selected" : ""}} value="Long Leave">Long Leave</option>
                                                                            <option {{$attendance->attendance == "Official" ? "selected" : ""}} value="Official">Official</option>
                                                                            <option {{$attendance->attendance == "Other" ? "selected" : ""}} value="Other">Other</option>
                                                                            <option {{$attendance->attendance == "Outstation" ? "selected" : ""}} value="Outstation">Outstation</option>
                                                                            <option {{$attendance->attendance == "Prep Leave" ? "selected" : ""}} value="Prep Leave">Prep Leave</option>
                                                                            <option {{$attendance->attendance == "Sick" ? "selected" : ""}} value="Sick">Sick</option>
                                                                            <option {{$attendance->attendance == "Wedding" ? "selected" : ""}} value="Wedding">Wedding</option>
                                                                        </select>
                                                                {{-- <input class="form-check-input leave-radio"
                                                                       type="radio"
                                                                       name="attendance[{!! $attendance->student->id !!}]"
                                                                       id="leave_{!! $attendance->student->id !!}"
                                                                       value="L"
                                                                       data-id="{!! $attendance->student->id !!}" {{ $attendance->attendance == 'L' ? 'checked' : '' }}>
                                                                Leave --}}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>


                                            <div id="loadData"></div>


                                            <div style="margin-top: 20px">
                                                <button type="submit" class="btn btn-primary">Update</button>
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


            $('.branch_select').on('change', function () {

                branch_id = $(this).val();
                if (branch_id == null) {
                    branch_id = {!! $studentAttendance->branch_id !!}
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_class').empty();

                        data.forEach(function (academic_class) {
                            var selectsection = academic_class.id == '{{ $studentAttendance->class_id }}' ? 'selected' : '';

                            sectionDropdown.append('<option value="' + academic_class.id + '" ' + selectsection + '>' + academic_class.name + '</option>');
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
        var class_id;
        $(document).ready(function () {
            {{--function fetch_student() {--}}
            {{--    var formData = $('#form_validation').serialize();--}}
            {{--    var loader = $('<div class="loader"></div>').appendTo('body');--}}

            {{--    $.ajax({--}}
            {{--        url: "{{ route('academic.student.data') }}",--}}
            {{--        type: 'POST',--}}
            {{--        data: formData,--}}
            {{--        headers: {--}}
            {{--            'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
            {{--        },--}}
            {{--        success: function (data) {--}}
            {{--            loader.remove();--}}
            {{--            $('#loadData').html(data);--}}
            {{--        },--}}
            {{--        error: function (request, error) {--}}
            {{--            loader.remove();--}}
            {{--            console.log("Request: " + JSON.stringify(request));--}}
            {{--        }--}}
            {{--    });--}}
            {{--}--}}


            $('.select_class').on('change', function () {
                class_id = $(this).val();
                if (class_id == null) {
                    class_id = {!! $studentAttendance->class_id !!}
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSections') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_section').empty();
                        sectionDropdown.append('<option>Select Section</option>');

                        data.forEach(function (section) {
                            var selectsection = section.id == '{{ $studentAttendance->section_id }}' ? 'selected' : '';

                            sectionDropdown.append('<option value="' + section.id + '" ' + selectsection + '>' + section.name + '</option>');

                        });
                        // fetch_student();

                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        })
    </script>

    <script>
        document.getElementById('check-all-leave').addEventListener('click', function () {
            var leaveRadios = document.getElementsByClassName('leave-radio');

            for (var l = 0; l < leaveRadios.length; l++) {
                leaveRadios[l].checked = true;
            }

        });
        document.getElementById('check-all-present').addEventListener('click', function () {
            var presentRadios = document.getElementsByClassName('present-radio');
            for (var i = 0; i < presentRadios.length; i++) {
                presentRadios[i].checked = true;
            }
        });

        $('#check-all-absent').on('click', function () {
            var absentButton = $('.absent-radio');
            for (var i = 0; i < absentButton.length; i++) {
                absentButton[i].checked = true;
            }
        });


        $('#users-table tr td').on('change', function () {
            // var id = $(this).find('input[type="radio"]').data('id');
            $(this).find('input[type="radio"]:checked').val();

        });
    </script>


@endsection


