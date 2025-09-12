@extends('admin.layouts.main')

@section('title')
Exam Term Create
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create Exam Term</h3>
                    <div class="row    mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{!! route('exam.exam_terms.index') !!}" class="btn btn-primary btn-md">
                                Back </a>
                        </div>
                    </div>

                    <form action="{!! route('exam.exam_terms.store') !!}" enctype="multipart/form-data" id="form_validation" autocomplete="off" method="post">
                        @csrf
                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:20px;">

                                <div class="row">

                                    <div class="col-md-4">
                                        <label for="Academic"><b>Academic Session <span class="text-danger">*</span></b></label>
                                        <select name="session_id" class="form-control session_select  select2 basic-single" required id="session_id">
                                            <option value="" selected disabled>Select Session</option>
                                            @foreach($sessions as $key => $item)
                                                <option value="{!! $key !!}">{!! $item !!}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="branch_Style"><b>Campus <span class="text-danger">*</span></b></label>
                                            </div>
                                            <select name="branch_id" class="form-control  select2 basic-single " required>
                                                <option value="" selected disabled>Select Branch</option>
                                                @foreach($branches as $item)
                                                    <option value="{!! $item->id !!}">{!! $item->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="branch_Style"><b>Term Id <span class="text-danger">*</span></b></label>
                                            <input type="text" class="form-control" id="term_id_display" disabled required>
                                            <input type="hidden" name="term_id" id="term_id">
                                        </div>
                                    </div>

                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="term_heading"><b>Progress Report Heading <span class="text-danger">*</span></b></label>
                                            </div>
                                            <input type="text" class="form-control" name="progress_heading" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="term_heading"><b>Start Date <span class="text-danger">*</span></b></label>
                                            </div>
                                            <input type="date" class="form-control start_date" name="start_date" min="{{ now()->toDateString() }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="term_heading"><b>End Date <span class="text-danger">*</span></b></label>
                                            </div>
                                            <input type="date" class="form-control end_date" name="end_date" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="term_heading"><b>Date Of Issue <span class="text-danger">*</span></b></label>
                                            </div>
                                            <input type="date" class="form-control start_date" name="issue_date" value="{{ now()->format('Y-m-d') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="term_heading"><b>Term Desc <span class="text-danger">*</span></b></label>
                                            </div>
                                            <input type="text" class="form-control term_desc" name="term_desc" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="term_heading"><b>Total Month <span class="text-danger">*</span></b></label>
                                            </div>
                                            <input type="text" class="form-control total_month" name="total_month" required>
                                        </div>
                                    </div>
                                </div>
                                <div id="coordinatorRows">
                                    <div class="row coordinator-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="term_heading"><b>Coordinator 1 <span class="text-danger">*</span></b></label>
                                                </div>
                                                <input type="text" class="form-control" name="coordinator_1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Academic"><b>Staff Name 1<span class="text-danger">*</span></b></label>
                                            <select name="staff_id_1" class="form-control session_select select2 basic-single" required>
                                                <option value="" selected disabled>Select Session</option>
                                                @foreach($hrm_employees as $hrm_employee)
                                                    <option value="{{ $hrm_employee->id }}">
                                                        {{ $hrm_employee->emp_id . " " . $hrm_employee->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove_coordinator" style="margin-top:32px; display:none;">Remove</button>
                                        </div>
                                    </div>
                                    <div class="row coordinator-row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="term_heading"><b>Coordinator 2 <span class="text-danger">*</span></b></label>
                                                </div>
                                                <input type="text" class="form-control" name="coordinator_2" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="Academic"><b>Staff Name 2<span class="text-danger">*</span></b></label>
                                            <select name="staff_id_2" class="form-control session_select select2 basic-single" required>
                                                <option value="" selected disabled>Select Session</option>
                                                @foreach($hrm_employees as $hrm_employee)
                                                    <option value="{{ $hrm_employee->id }}">
                                                        {{ $hrm_employee->emp_id . " " . $hrm_employee->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove_coordinator" style="margin-top:32px; display:none;">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary add_more_coordinator">Add More Coordinator</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
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
        $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
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

            $('.start_date').on('change', function () {
                const start_date = $(this).val();
                $('.end_date').attr('min', start_date);
            })

            $('.branch_select').on('change', function () {

                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var classDropdown = $('.class_select').empty();
                        classDropdown.append('<option value="">Select class</option>');

                        data.forEach(function (academic_class) {
                            classDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
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
        $(function () {
            function nextCoordinatorIndex() {
                var maxIndex = 0;
                $('#coordinatorRows').find('input[name^="coordinator_"]').each(function () {
                    var m = $(this).attr('name').match(/^coordinator_(\d+)$/);
                    if (m) {
                        var idx = parseInt(m[1], 10);
                        if (idx > maxIndex) { maxIndex = idx; }
                    }
                });
                return maxIndex + 1;
            }

            $('.add_more_coordinator').on('click', function () {
                var nextIndex = nextCoordinatorIndex();
                if (nextIndex > 4) {
                    alert('You can add up to 4 coordinators.');
                    return;
                }
                var $template = $('#coordinatorRows .coordinator-row:last').clone();

                // reset values and update names
                $template.find('input[name^="coordinator_"]').val('').attr('name', 'coordinator_' + nextIndex).prop('required', nextIndex <= 2);

                // clean any existing select2 markup in clone
                $template.find('span.select2').remove();
                var $select = $template.find('select[name^="staff_id_"]');
                $select.val('');
                $select.attr('name', 'staff_id_' + nextIndex).prop('required', nextIndex <= 2);
                $select.removeClass('select2-hidden-accessible').show();

                // update labels
                $template.find('label.term_heading b').html('Coordinator ' + nextIndex + (nextIndex <= 2 ? ' <span class="text-danger">*</span>' : ''));
                $template.find('label[for="Academic"] b').html('Staff Name ' + nextIndex + (nextIndex <= 2 ? '<span class="text-danger">*</span>' : ''));

                // show remove button for rows > 2
                $template.find('.remove_coordinator').show();

                $('#coordinatorRows').append($template);

                if ($.fn.select2) {
                    $select.select2();
                }
            });

            // remove handler
            $('#coordinatorRows').on('click', '.remove_coordinator', function () {
                $(this).closest('.coordinator-row').remove();
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.end_date').on('change', function () {

                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                var startDateValue = $('.start_date').val();
                var startDate = new Date(startDateValue);
                var endDateValue = $('.end_date').val();
                var endDate = new Date(endDateValue);
                var startMonthName = monthNames[startDate.getMonth()];
                var endMonthName = monthNames[endDate.getMonth()];
                var datediff = (endDate.getFullYear() * 12 + endDate.getMonth()) - (startDate.getFullYear() * 12 + startDate.getMonth())
                $('.total_month').val(datediff);

                $('.term_desc').val(startMonthName + ' - ' + endMonthName);
            });
        });

        $(document).ready(function () {
            $('#session_id, select[name="branch_id"]').on('change', function () {
                let session_id = $('#session_id').val();
                let branch_id = $('select[name="branch_id"]').val();

                if (session_id && branch_id) {
                    $.ajax({
                        url: '{{ route("exam.exam_terms.generate_term_id") }}',
                        method: 'GET',
                        data: {
                            session_id: session_id,
                            branch_id: branch_id
                        },
                        success: function (response) {
                            $('#term_id').val(response.term_id); // hidden input for backend
                            $('#term_id_display').val(response.term_id); // visible disabled field
                        },
                        error: function () {
                            $('#term_id').val('');
                            $('#term_id_display').val('');
                        }
                    });
                }
            });
        });
    </script>
@endsection