@extends('admin.layouts.main')

@section('title')
    Marks Input Edit
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Marks Input</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('exam.marks_input.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('exam.marks_input.update',$marksInput->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            {{method_field('PUT')}}
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label for="company_id"><b>Company:</b></label>
                                    <select name="company_id"
                                            class="form-select select2 basic-single mt-3" id="companySelect"
                                            aria-label=".form-select-lg example" required>
                                        @foreach($companies as $item)
                                            <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="session_id"> <b> Academic Session </b></label>
                                    <select name="session_id"
                                            class="form-select select2 basic-single mt-3 session_select"
                                            aria-label=".form-select-lg example" required>
                                        {{-- <option value="" selected>Select Session</option> --}}
                                        @foreach($sessions as $key => $item)
                                            <option value="{{$key}}" @if($key == $marksInput->session_id) selected @endif>{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="branch_id"><b>Branch: </b></label>
                                    <select name="branch_id"
                                            class="form-select select2 basic-single mt-3 branch_select" id="branch_select"
                                            aria-label=".form-select-lg example" required>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="class_id"><b>Class: *</b></label>
                                    <select required name="class_id"
                                            class="form-select select2 basic-single mt-3 select_class"
                                            aria-label=".form-select-lg example">

                                    </select>
                                </div>

                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="section_id"><b>Section: *</b></label>
                                    <select required name="section_id"
                                            class="form-select select2 basic-single mt-3 select_section"
                                            aria-label=".form-select-lg example">
                                        <option value="" selected>Select Section</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="subject_id"><b>Subject: *</b></label>
                                    <select required name="subject_id"
                                            class="form-select select2 basic-single mt-3 select_course"
                                            aria-label=".form-select-lg example">
                                        <option value="" selected>Select Subject</option>
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label for="component_id"><b>Component: </b></label>
                                    <select name="component_id"
                                            class="form-select select2 basic-single mt-3 component_id" id="component_id"
                                            aria-label=".form-select-lg example" required>


                                    </select>
                                </div>

                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="sub_component_id"><b>Sub Component: </b></label>
                                    <select name="sub_component_id"
                                            class="form-select select2 basic-single mt-3 sub_component_id"
                                            id="sub_component_id"
                                            aria-label=".form-select-lg example" required>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div id="loadData"></div>
                            </div>

                            @if($marksInput->mark_entries && $marksInput->mark_entries->count())
                                <div class="row mt-5">
                                    <div class="col-12">
                                          <div class="col-12 d-flex justify-content-between align-items-center mb-5">
                                            <h5 class="mb-0">Allocated Marks</h5>
                                            <button type="button" class="btn btn-success btn-sm" id="screenshotBtn">Download Screenshot</button>
                                        </div>
                                        <div id="marksScreenshotArea">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student</th>
                                                        <th>Max Marks</th>
                                                        <th>Allocated Marks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($marksInput->mark_entries as $index => $entry)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $entry->student->first_name . ' ' . $entry->student->last_name ?? 'N/A' }}</td>
                                                            <td>{{ $entry->max_marks }}</td>
                                                            <td>
                                                                <input type="hidden" name="entries[{{ $entry->id }}][entry_id]" value="{{ $entry->id }}">
                                                                <input type="number" name="entries[{{ $entry->id }}][allocated_marks]"
                                                                    value="{{ $entry->allocated_marks }}" class="form-control"
                                                                    min="0" max="{{ $entry->max_marks }}" required>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"
                                        style="margin-bottom: 10px;margin-left: 10px;">Update
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>



    <script>
        $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });

    </script>


    <script>
        $(document).ready(function () {
            var branchId = {{ $marksInput->branch_id }};
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

                        branchesDropdown.append('<option value="" selected>Select Branch</option>');

                        data.forEach(function (branch) {
                            var selectedBranch = (branch.id == branchId) ? 'selected' : '';
                            branchesDropdown.append('<option value="' + branch.id + '"' + selectedBranch + '>' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();

            setTimeout(() => {
                var classId = {{ $marksInput->class_id }};
                var branch_id = $('.branch_select').val();
                $('.branch_select').on('change', function () {
                    var loader = $('<div class="loader"></div>').appendTo('body');
                    // branch_id = $(this).val();
                    $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        loader.remove();
                        var classDropdown = $('.select_class').empty();
                        classDropdown.append('<option value="" selected>Select class</option>');
                        data.forEach(function (academic_class) {
                            var selectedClass = (academic_class.id == classId) ? 'selected' : '';
                            classDropdown.append('<option value="' + academic_class.id + '"' + selectedClass + '>' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                        loader.remove();
                        console.error('Error fetching classes:', error);
                    }
                });
                }).change();
            }, 2000);
            var sectionId = {{ $marksInput->section_id }};
            setTimeout(() => {
                $('.select_class').on('change', function() {
                   var loader = $('<div class="loader"></div>').appendTo('body');
                    var class_id = $(this).val();
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('academic.fetchSections') }}',
                        data: {
                            class_id : class_id,
                        },
                        success: function(data) {
                            loader.remove();
                            var sectionDropDown = $('.select_section').empty();
                            sectionDropDown.append('<option value="" selected>Select Section</option>');
                            data.forEach(function (section){
                                var selectedSection = (section.id == sectionId) ? 'selected' : '';
                                sectionDropDown.append('<option value="' + section.id + '"' + selectedSection + '>' + section.name +'</option>')
                            })
                        }, error: function(error){
                            loader.remove();
                            console.error('Error fetching sections' + error);
                        }
                    })
                }).change();
                var subjectId = {{ $marksInput->course_id }};
                $('.select_class').on('change', function () {
                    var class_id = $(this).val();
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('academic.fetchSubject') }}',
                        data: {
                            class_id: class_id
                        },
                        success: function (data) {
                            // loader.remove();
                            var classSubjectDropdown = $('.select_course').empty();
                            classSubjectDropdown.append('<option value="" selected>Select Subject</option>');

                            data.forEach(function (subject) {
                                selectedSubject = (subject.id == subjectId) ? 'selected' : '';
                                classSubjectDropdown.append('<option value="' + subject.id + '"' + selectedSubject +'>' + subject.name + '</option>');
                            });
                        },
                        error: function (error) {
                            loader.remove();
                            console.error('Error fetching subjects:', error);
                        }
                    });
                }).change();
            }, 3500);



            var componentId = {{ $marksInput->component_id }};

            
           setTimeout(() => {
                $('.select_course').on('change', function () {
                    var course_id = {{ $marksInput->subject_id }};
                    console.log("The course id is", course_id);

                    $.ajax({
                        type: 'GET',
                        url: '{{ route('academic.fetchComponentSubject') }}',
                        data: {
                            course_id: course_id
                        },
                        success: function (data) {
                            var componentSubjectDropdown = $('.component_id').empty();
                            componentSubjectDropdown.append('<option value="" selected>Select Component</option>');

                            if (Array.isArray(data) && data.length > 0) {
                                data.forEach(function (item) {
                                    var selectedComponent = (item.id == {{ $marksInput->component_id ?? 'null' }}) ? 'selected' : '';
                                    componentSubjectDropdown.append('<option value="' + item.id + '" ' + selectedComponent + '>' + item.name + '</option>');
                                });
                            } else {
                                componentSubjectDropdown.append('<option>No components available</option>');
                            }
                        },
                        error: function (error) {
                            console.error('Error fetching component:', error);
                        }
                    });
                }).change();
            }, 3500);



            var subComponentId = {{ $marksInput->sub_component_id }};
            setTimeout(() => {
                $('.component_id').on('change', function () {
                var component_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('exam.fetchSubComponent') }}',
                    data: {
                        component_id: component_id
                    },
                    success: function (data) {
                        // loader.remove();
                        var subComponentDropdown = $('.sub_component_id').empty();
                        subComponentDropdown.append('<option value="" selected>Select SubComponent</option>');

                        data.forEach(function (subComponent) {
                            selectedSubComponent = (subComponent.id == subComponentId) ? 'selected' : '';
                            subComponentDropdown.append('<option value="' + subComponent.id + '"' + selectedSubComponent + '>' + subComponent.comp_name + '</option>');
                        });
                    },
                    error: function (error) {
                        loader.remove();
                        console.error('Error fetching sub components:', error);
                    }
                });
            }).change();
            }, 5000);


            function loadData() {
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
                
                }).change();
            });

            $(document).on('input', '.allocated-mark', function () {
                const $input = $(this);
                const max = parseFloat($input.data('max'));
                const val = parseFloat($input.val());

                const errorSpan = $input.closest('td').find('.mark-error-msg');

                if (val > max) {
                    errorSpan.removeClass('d-none');
                    $input.val('');
                } else {
                    errorSpan.addClass('d-none');
                }
            });


          document.getElementById('screenshotBtn').addEventListener('click', function () {
                const target = document.getElementById('marksScreenshotArea');
                if (!target) {
                    alert("Unable to capture screenshot: element not found.");
                    return;
                }

                html2canvas(target, { scrollY: -window.scrollY }).then(canvas => {
                    const link = document.createElement('a');
                    link.download = 'allocated_marks_screenshot.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            });


        })
    </script>
@endsection
