@extends('admin.layouts.main')

@section('title')
    Component edit
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Update Component</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('exam.components.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('exam.components.update',$component->id) !!}"
                              enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            {{method_field('put')}}
                            <div class="row mt-3">
                                <div class="col md-3">
                                    <label for="name">Name</label>
                                    <input type="text" value="{!! $component->name !!}" class="form-control"
                                           name="name">
                                </div>

                                <div class="col-md-3">
                                    <label for="branches"><b>Company:</b></label>
                                    <select name="company_id"
                                            class="form-select select2 disable_select basic-single mt-3"
                                            id="companySelect"
                                            aria-label=".form-select-lg example" required>
                                        @foreach($companies as $item)
                                            <option
                                                value="{{$item->id}}" {{ $component->company_id == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="Company-name"> <b> Academic Session </b></label>
                                    <select name="session_id"
                                            class="form-select select2 basic-single disable_select mt-3 session_select"
                                            aria-label=".form-select-lg example" required>
                                        <option value="" selected disabled>Select Session</option>
                                        @foreach($sessions as $key => $item)
                                            <option
                                                value="{{$key}}" {{ $component->session_id == $key ? 'selected' : '' }}>{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="branches"><b>Branch: </b></label>
                                    <select name="branch_id"
                                            class="form-select select2 basic-single disable_select mt-3 branch_select"
                                            aria-label=".form-select-lg example" required>

                                    </select>
                                </div>

                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label for="branches"><b>Class: </b></label>
                                    <select name="class_id"
                                            class="form-select select2 disable_select basic-single mt-3 select_class"
                                            aria-label=".form-select-lg example" required>

                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label for="branches"><b>Section: </b></label>
                                    <select name="section_id"
                                            class="form-select select2 disable_select basic-single mt-3 select_section"
                                            aria-label=".form-select-lg example" required>
                                        <option value="" selected disabled>Select Section</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="branches"><b>Subject: </b></label>
                                    <select name="subject_id"
                                            class="form-select select2 disable_select basic-single mt-3 select_course"
                                            aria-label=".form-select-lg example" required>
                                        <option value="" selected disabled>Select Subject</option>
                                    </select>
                                </div>


                            </div>

                            <div class="row mt-5">
                                <div>
                                    <table id="users-table" class="table table-bordered table-striped datatable mt-3"
                                           style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th>Sr.#</th>
                                            <th>Type</th>
                                            <th>Weightage (%)</th>
                                            <th>Marks</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php($i=1)
                                        @foreach($component->componentData as $componentData)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>
                                                    {{@$componentData->test_type->name ?? 'N/A'}}
                                                    <input type="hidden" name="type_id[]"
                                                           value="{{$componentData->type_id}}">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control weightage-input"
                                                           name="weightage[]" value="{!! $componentData->weightage !!}"
                                                           id="weightage-{{$componentData->id}}">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control total-marks-input"
                                                           name="total_marks[]" value="{!! $componentData->total_marks !!}"
                                                           id="total_marks-{{$componentData->id}}">
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div>
                                        Total Weightage: <span id="total-weightage">0</span>%
                                    </div>
                                    <div id="error-message" style="color: red; display: none;">
                                        The total weightage cannot exceed 100%. The field causing the error has been
                                        reset to 0.
                                    </div>
                                    <div>
                                        Total Marks: <span id="total-marks">0</span>
                                    </div>
                                    <div id="marks-error-message" style="color: red; display: none;">
                                        The total marks cannot exceed 100. The field causing the error has been reset to 0.
                                    </div>


                                </div>

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
        var branch_id;

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

                            var selectedbranches = branch.id == '{{ $component->branch_id  }}' ? 'selected' : '';
                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectedbranches + '>' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


            $('.branch_select').on('change', function () {
                branch_id = $(this).val();
                if (branch_id == null) {
                    branch_id = {!! $component->branch_id !!}
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var classDropdown = $('.select_class').empty();

                        data.forEach(function (academic_class) {

                            var selectedclass = academic_class.id == '{{ $component->class_id }}' ? 'selected' : '';
                            classDropdown.append('<option value="' + academic_class.id + '" ' + selectedclass + '>' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        });
    </script>

    <script>
        var class_id;
        $(document).ready(function () {
            $('.select_class').on('change', function () {
                class_id = $(this).val();
                if (class_id == null) {
                    class_id =  {!! $component->class_id !!}
                }

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSections') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_section').empty();

                        sectionDropdown.append('<option value="">Select section</option>');

                        data.forEach(function (section) {
                            var selectedsection = section.id == '{{ $component->section_id }}' ? 'selected' : '';
                            sectionDropdown.append('<option value="' + section.id + '" ' + selectedsection + '>' + section.name + '</option>');
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
            $('.select_class').on('change', function () {
                class_id = $(this).val();
                if (class_id == null) {
                    class_id =  {!! $component->class_id !!}
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchSubjects') }}',
                    data: {
                        class_id: class_id
                    },
                    success: function (data) {
                        var classSubjectDropdown = $('.select_course').empty();
                        // classSubjectDropdown.append('<option>Select Subject</option>');

                        data.forEach(function (subject) {
                            var selectedSubject = subject.id == '{{ $component->subject_id }}' ? 'selected' : '';
                            classSubjectDropdown.append('<option value="' + subject.id + '" ' + selectedSubject + '>' + subject.name + '</option>');
                        });

                    },
                    error: function (error) {
                        console.error('Error fetching subjects:', error);
                    }
                });
            }).change();
        })
    </script>

    <!-- <script>
        $(document).ready(function () {

            function updateTotalWeightage(input) {
                let total = 0;
                let exceeded = false;

                $('.weightage-input').each(function () {
                    let value = parseFloat($(this).val()) || 0;
                    total += value;
                    if (total > 100 && !exceeded) {
                        exceeded = true;
                        $(input).val(0);
                        total -= value;
                    }
                });

                $('#total-weightage').text(total);

                if (exceeded) {
                    $('#error-message').show();
                } else {
                    $('#error-message').hide();
                }
            }

            function updateTotalMarks(input) {
                let total = 0;
                let exceeded = false;

                $('.total-marks-input').each(function () {
                    let value = parseFloat($(this).val()) || 0;
                    total += value;
                    if (total > 100 && !exceeded) {
                        exceeded = true;
                        $(input).val(0);
                        total -= value;
                    }
                });

                $('#total-marks').text(total);

                if (exceeded) {
                    $('#marks-error-message').show();
                } else {
                    $('#marks-error-message').hide();
                }
            }

            
            $('.weightage-input').on('input', function () {
                updateTotalWeightage(this);
            });

            $('.total-marks-input').on('input', function () {
                updateTotalMarks(this);
            });

            
            updateTotalWeightage();
            updateTotalMarks();

            
            $('.disable_select').select2({disabled: 'readonly'});
    });
</script> -->

<script>
    $(document).ready(function () {

        function updateTotalWeightage(input) {
            let total = 0;
            let exceeded = false;

            $('.weightage-input').each(function () {
                let value = parseFloat($(this).val()) || 0;
                total += value;
                if (total > 100 && !exceeded) {
                    exceeded = true;
                    $(input).val(0);
                    total -= value;
                }
            });

            $('#total-weightage').text(total);

            if (exceeded) {
                $('#error-message').show();
            } else {
                $('#error-message').hide();
            }
        }

        function updateTotalMarks() {
            let total = 0;

            $('.total-marks-input').each(function () {
                let value = parseFloat($(this).val()) || 0;
                total += value;
            });

            $('#total-marks').text(total);
        }

        $('.weightage-input').on('input', function () {
            updateTotalWeightage(this);
        });

        $('.total-marks-input').on('input', function () {
            updateTotalMarks();
        });

        // initialize totals on page load
        updateTotalWeightage();
        updateTotalMarks();

        // disable select2 (readonly mode)
        $('.disable_select').select2({disabled: 'readonly'});
    });
</script>



@endsection

