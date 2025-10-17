@extends('admin.layouts.main')

@section('title')
    Subjects | Edit
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Subjects</h3>
                        <div class="row mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.subjects.index') !!}" class="btn btn-primary btn-md">
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
                        <form action="{!! route('academic.subjects.update',$course->id) !!}"
                              enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')

                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label for="branches"><b>Company: *</b></label>
                                            <select required name="company_id"
                                                    class="form-select select2 basic-single mt-3" id="companySelect"
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select Company</option>
                                                @foreach($companies as $item)
                                                    <option
                                                        value="{{$item->id}}" {!! $course->company_id == $item->id ? 'selected' : '' !!}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Academic Session </b></label>
                                            <select  name="session_id"
                                                     class="form-select select2 basic-single mt-3 session_select"
                                                     aria-label=".form-select-lg example">
                                                <option value="">Select Session</option>
                                                @foreach($formattedSessions as $key => $item)
                                                    <option value="{{$key}}" {!! $course->session_id == $key ? 'selected' : '' !!} >{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Branch: *</b></label>
                                            <select required name="branch_id"
                                                    class="form-select select2 basic-single mt-3 branch_select"
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select Branch</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}" {!! $course->branch_id == $branch->id ? 'selected' : '' !!}>{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>



                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label for="branches"><b>Class: *</b></label>
                                            <select required name="class_id"
                                                    class="form-select select2 basic-single mt-3 class_select"
                                                    aria-label=".form-select-lg example">
                                                    @foreach($classes as $class)
                                                        <option value="{{$class->id}}" {{$course->class_id == $class->id ? 'selected' : ''}}>{{$class->name}}</option>
                                                    @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="branches"><b>Active Section: *</b></label>
                                            <select required name="active_session_id"
                                                    class="form-select select2 basic-single mt-3 select_active_session"
                                                    aria-label=".form-select-lg example">

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">

                                        <div class="col-md-4">
                                            <label for="branches"><b>Subject Type: *</b></label>
                                            <select required name="course_type_id"
                                                    class="form-select select2 basic-single mt-3 school_select"
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select Course</option>

                                                @foreach($course_types as $item)
                                                    <option value="{{$item->id}}" {!! $course->course_type_id == $item->id ? 'selected' : '' !!}>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="name"><b>Subject Name </b></label>
                                            <input type="text" name="name" value="{!! $course->name !!}"
                                                   placeholder="name" class="form-control"
                                                   required>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="name"><b>Subject Code </b></label>
                                            <input type="text" name="subject_code" value="{!! $course->subject_code !!}"
                                                   class="form-control"
                                            >
                                        </div>
                                    </div>
                                    <div style="margin-top: 20px">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
            $('#companySelect').on('change', function () {
                loader('show');
                var selectedCompanyId = $('#companySelect').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {
                        var branchesDropdown = $('.branch_select').empty();

                        branchesDropdown.append('<option value="">Select Branch</option>');

                        data.forEach(function (branch) {

                            var selectedBranch = branch.id == '{{ $course->branch_id }}' ? 'selected' : '';

                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectedBranch + '>' + branch.name + '</option>');

                        });
                    },
                    error: function (error) {
                        toastr.error('Error fetching branches:', error);
                        console.error('Error fetching branches:', error);
                    }
                });
                loader('hide');
            }).change();

        })

    </script>

    <script>
        var branch_id;

        $(document).ready(function () {
            $('.branch_select').on('change', function () {
                console.log('branch changed');
                loader('show');
                branch_id = $(this).val();
                console.log('branch_id:', branch_id);
                
                if (branch_id == null || branch_id == '') {
                    loader('hide');
                    return;
                }
                
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        console.log('Classes data:', data);
                        var sectionDropdown = $('.class_select').empty();
                        
                        // Add default option
                        sectionDropdown.append('<option value="">Select Class</option>');

                        data.forEach(function (academic_class) {
                            var selectedClass = academic_class.id == '{{ $course->class_id }}' ? 'selected' : '';
                            sectionDropdown.append('<option value="' + academic_class.id + '" ' + selectedClass + '>' + academic_class.name + '</option>');
                        });
                        
                        loader('hide');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching classes:', error);
                        console.error('Response:', xhr.responseText);
                        toastr.error('Error fetching classes: ' + error);
                        loader('hide');
                    }
                });
            });
            
            // Trigger change event on page load if branch is already selected
            if ($('.branch_select').val()) {
                $('.branch_select').trigger('change');
            }
        });
    </script>

    <script>
        var Class_id;
        $(document).ready(function () {
            $('.class_select').on('change', function () {
                loader('show');
                Class_id = $(this).val();
                
                if (Class_id == null || Class_id == '') {
                    loader('hide');
                    return;
                }

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetch.active_sessions') }}',
                    data: {
                        class_id: Class_id
                    },
                    success: function (data) {
                        console.log('Active session data:', data);
                        let ActiveSessionDropdown = $('.select_active_session').empty();
                        
                        if (data && data.id) {
                            var startDate = new Date(data.start_date);
                            var endDate = new Date(data.end_date);

                            var startYear = startDate.getFullYear().toString().slice(-2);
                            var endYear = endDate.getFullYear().toString().slice(-2);

                            var sessionNameWithDate = data.name + ' ' + startYear + '-' + endYear;
                            var selectedactiveSection = data.id == '{{ $course->active_session_id }}' ? 'selected' : '';

                            ActiveSessionDropdown.append('<option value="' + data.id + '" ' + selectedactiveSection + '>' + sessionNameWithDate + '</option>');
                        } else {
                            ActiveSessionDropdown.append('<option value="">No active session found</option>');
                        }
                        
                        loader('hide');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching active session:', error);
                        console.error('Response:', xhr.responseText);
                        toastr.error('Error fetching active session: ' + error);
                        loader('hide');
                    }
                });
            });
            
            // Trigger change event on page load if class is already selected
            if ($('.class_select').val()) {
                $('.class_select').trigger('change');
            }
        });
    </script>
@endsection

