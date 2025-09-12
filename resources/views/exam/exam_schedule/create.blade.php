@extends('admin.layouts.main')

@section('title')
    Exam Detail Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                         @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Whoops! Something went wrong.</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Exam Schedule</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('exam.exam_schedules.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('exam.exam_schedules.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:20px;">
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label for="company"><b>Company:</b></label>
                                            <select name="company_id"
                                                    class="form-select select2 mt-3" id="companySelect"
                                                    aria-label=".form-select-lg example" required>
                                                @foreach($companies as $item)
                                                    <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="branches"><b>Branch: </b></label>
                                            <select name="branch_id"
                                                    class="form-select select2 basic-single mt-3 branch_select"
                                                    aria-label=".form-select-lg example" required>

                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="branches"><b>Term: *</b></label>
                                            <select required name="exam_term_id"
                                                    class="form-select select2 basic-single mt-3 exam_term"
                                                    aria-label=".form-select-lg example">

                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="branches"><b>Test: *</b></label>
                                            <select name="test_type_id"
                                                    class="form-select select2 basic-single mt-3"
                                                    aria-label=".form-select-lg example" required>
                                                @foreach($tests as $item)
                                                    <option value="{{$item->id}}">{{$item->test_name}}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <label for="branches"><b>Class: *</b></label>
                                            <select required name="class_id"
                                                    class="form-select select2 basic-single mt-3 select_class"
                                                    aria-label=".form-select-lg example">

                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-5">
                                        <div id="loadData"></div>
                                    </div>
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

            $('.branch_select').on('change', function () {
                var branch_id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchExamTerm') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var termDropdown = $('.exam_term').empty();
                        termDropdown.append('<option value="" selected disabled>Select Exam Term</option>');

                        data.forEach(function (academic_class) {
                            let isSelected = '';

                            @if(empty($exam_schedule_detail->test_type_id) && isset($exam_schedule_detail->exam_term_id))
                                if (academic_class.id == '{{ $exam_schedule_detail->exam_term_id }}') {
                                    isSelected = 'selected';
                                }
                            @endif

                            termDropdown.append(
                                '<option value="' + academic_class.id + '" ' + isSelected + '>' + academic_class.term_desc + '</option>'
                            );
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching exam terms:', error);
                    }
                });
            });

        });
    </script>


    <script>
        function loadData() {
            var branch_id = $('.branch_select').val();
            var class_id = $('.select_class').val();
            var exam_schedule_id = '{{ $exam_schedule_detail->id ?? '' }}';

            $.ajax({
                url: "{{route('exam.classSubject.data')}}",
                type: 'POST',
                data: {
                    branch_id: branch_id,
                    class_id: class_id,
                    exam_schedule_id: exam_schedule_id,
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    $('#loadData').html(data);
                },
                error: function (request, error) {
                    console.log("Request: " + JSON.stringify(request));
                }
            });
        }


        $('.select_class').on('change', function () {
            loadData();
        });
    </script>

@endsection

