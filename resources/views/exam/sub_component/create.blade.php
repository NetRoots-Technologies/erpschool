@extends('admin.layouts.main')

@section('title')
    Sub Component Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Sub Component </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('exam.sub_components.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('exam.sub_components.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="row mt-3">

                                <div class="col-md-3">
                                    <label for="branches"><b>Company:</b></label>
                                    <select name="company_id"
                                            class="form-select select2 basic-single mt-3" id="companySelect"
                                            aria-label=".form-select-lg example" required>
                                        @foreach($companies as $item)
                                            <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="col-md-3">
                                    <label for="Company-name"> <b> Academic Session </b></label>
                                    <select name="session_id"
                                            class="form-select select2 basic-single mt-3 session_select"
                                            aria-label=".form-select-lg example" required>
                                        <option value="" selected disabled>Select Session</option>
                                        @foreach($sessions as $key => $item)
                                            <option value="{{$key}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="col-md-3">
                                    <label for="branches"><b>Branch: </b></label>
                                    <select name="branch_id"
                                            class="form-select select2 basic-single mt-3 branch_select"
                                            aria-label=".form-select-lg example" required>

                                    </select>
                                </div>

                                <div class="col-md-3">
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
                                        <option value="" selected disabled>Select Section</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="branches"><b>Subject: *</b></label>
                                    <select required name="subject_id"
                                            class="form-select select2 basic-single mt-3 select_course"
                                            aria-label=".form-select-lg example">
                                        <option value="" disabled selected>Select Subject</option>
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label for="branches"><b>Component: </b></label>
                                    <select name="component_id"
                                            class="form-select select2 basic-single mt-3 component_id" id="component_id"
                                            aria-label=".form-select-lg example" required>


                                    </select>
                                </div>

                            </div>

                            <div class="row mt-5">
                                <div id="loadData"></div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"
                                        style="margin-bottom: 10px;margin-left: 10px;">Save
                                </button>
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
            $('.test_name').on('input', function () {
                var test_name = $(this).val();
                var initalVal = test_name.substring(0, 3);
                $('.initial').val(initalVal);
            });
        });
    </script>
    <script>
        $(document).ready(function () {

            $('#companySelect').on('change', function () {
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
                            branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


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
                        sectionDropdown.append('<option value="" selected disabled>Select Class</option>');
                        data.forEach(function (academic_class) {
                            sectionDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
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
                        sectionDropdown.append('<option>Select Section</option>');

                        data.forEach(function (section) {
                            sectionDropdown.append('<option value="' + section.id + '">' + section.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            });


            $('.select_class').on('change', function () {
                var class_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSubject') }}',
                    data: {
                        id: class_id
                    },
                    success: function (data) {
                        var classSubjectDropdown = $('.select_course').empty();
                        classSubjectDropdown.append('<option>Select Subject</option>');

                        data.forEach(function (subject) {
                            classSubjectDropdown.append('<option value="' + subject.id + '">' + subject.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching subjects:', error);
                    }
                });


            });
        })
    </script>

    <script>
        $(document).ready(function () {
            $('.select_course').on('change', function () {
                var course_id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchComponentSubject') }}',
                    data: {
                        course_id: course_id
                    },
                    success: function (data) {
                        console.log(data);
                        var componentSubjectDropdown = $('.component_id').empty();
                        componentSubjectDropdown.append('<option value="">Select Component</option>');

                        // Iterate over multiple components if data is an array
                        if (Array.isArray(data)) {
                            data.forEach(function (component) {
                                componentSubjectDropdown.append(
                                    '<option value="' + component.id + '">' + component.name + '</option>'
                                );
                            });
                        } else {
                            console.warn('Expected array, got:', data);
                        }
                    },
                    error: function (error) {
                        console.error('Error fetching components:', error);
                    }
                });
            });
        });
    </script>

{{--    <script>--}}
{{--        $(document).ready(function () {--}}
{{--            $('.component_id').on('change', function () {--}}
{{--                var component_id = $(this).val();--}}
{{--                $.ajax({--}}
{{--                    type: 'GET',--}}
{{--                    url: '{{ route('academic.fetchComponentData') }}',--}}
{{--                    data: {--}}
{{--                        component_id: component_id--}}
{{--                    },--}}
{{--                    success: function (data) {--}}
{{--                        console.log(data);--}}
{{--                        var componentSubjectDropdown = $('.component_data_id').empty();--}}
{{--                        componentSubjectDropdown.append('<option>Select Component Type</option>');--}}

{{--                        if (data) {--}}
{{--                            componentSubjectDropdown.append('<option value="' + data.id + '">' + data.name + '</option>');--}}
{{--                        }--}}
{{--                    },--}}
{{--                    error: function (error) {--}}
{{--                        console.error('Error fetching subjects:', error);--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}


    <script>
        function loadData() {
            // var branch_id = $('#selectBranch').val();
            var component_id = $('.component_id').val();
            var loader = $('<div class="loader"></div>').appendTo('body');

            $.ajax({

                url: "{{route('exam.sub-component.data')}}",
                type: 'POST',
                data: {
                    'component_id': component_id,
                },
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

        $(document).ready(function () {
            $('.component_id').on('change', function () {
                loadData();
            });
        });

    </script>

@endsection

