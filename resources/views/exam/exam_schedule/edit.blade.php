@extends('admin.layouts.main')

@section('title')
    Exam Detail Update
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
                        <h3 class="text-22 text-midnight text-bold mb-4"> Update Exam Schedule</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('exam.exam_schedules.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{{ route('exam.exam_schedules.update', $exam_schedule_detail->id) }}" 
                            method="POST" 
                            enctype="multipart/form-data"
                            id="form_validation" autocomplete="off">
                              @csrf
                            @method('PUT')
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:20px;">
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label for="company"><b>Company:</b></label>
                                            <select name="company_id"
                                                    class="form-select select2 mt-3" id="companySelect"
                                                    aria-label=".form-select-lg example" required>
                                                    @foreach($companies as $item)
                                                        <option value="{{ $item->id }}" {{ $exam_schedule_detail->company_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
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
                                    <input type="hidden" name="getvalue" value="1" id="getvalue">
                                    <div class="row mt-5" id="loadData2">
                                    <!-- Subjects -->
                                    <div class="col-md-3">
                                        <label><b>Subjects: *</b></label>
                                        <select name="course_id" class="form-select select2 mt-3">
                                            @if(count($classSubject) > 0)
                                                @foreach($classSubject as $item)
                                                    <option value="{{ $item->Subject->id }}" {{ $exam_schedule_detail->course_id == $item->Subject->id ? 'selected' : '' }}>
                                                        {{ $item->Subject->name }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="">Please Select Subject</option>
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Component -->
                                    <div class="col-md-3">
                                        <label><b>Component: *</b></label>
                                        <select name="component_id" class="form-select select2 mt-3">
                                            @foreach($components as $item)
                                                <option value="{{ $item->id }}" {{ $exam_schedule_detail->component_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Marks -->
                                    <div class="col-md-3">
                                        <label><b>Marks: *</b></label>
                                        <input type="text" class="form-control" name="marks" id="marks" value="{{ $exam_schedule_detail->marks }}">
                                    </div>

                                    <!-- Grade -->
                                    <div class="col-md-1 mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="grade" id="grade" {{ $exam_schedule_detail->grade ? 'checked' : '' }}>
                                            <label class="form-check-label" for="grade">Grade</label>
                                        </div>
                                    </div>

                                    <!-- Pass -->
                                    <div class="col-md-1 mt-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="pass" id="pass" {{ $exam_schedule_detail->pass ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pass">Pass</label>
                                        </div>
                                    </div>
                                </div><br>
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
            var selectedBranchId = '{{ $exam_schedule_detail->branch_id ?? '' }}'; // from Blade

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
                        var isSelected = (branch.id == selectedBranchId) ? 'selected' : '';
                        branchesDropdown.append(
                            '<option value="' + branch.id + '" ' + isSelected + '>' + branch.name + '</option>'
                        );
                    });

                    @if(isset($exam_schedule_detail->branch_id))
                        $('.branch_select').val('{{ $exam_schedule_detail->branch_id }}').trigger('change');
                    @endif
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
                        sectionDropdown.append('<option value="" disabled>Select Class</option>');
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
                        termDropdown.append('<option value="" disabled>Select Exam Term</option>');
                        data.forEach(function (academic_class) {
                            termDropdown.append('<option value="' + academic_class.id + '">' + academic_class.term_desc + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            });
        });
    </script>


    <script>
        function loadData() {

            var branch_id = $('.branch_select').val();
            var class_id = $('.select_class').val();
            $.ajax({

                url: "{{route('exam.classSubject.data')}}",
                type: 'POST',
                data: {
                    'branch_id': branch_id,
                    'class_id': class_id,
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    $("#loadData2").hide();
                    console.log("Updated Data",data);
                    $('#loadData').html(data);
                   
                },
                error: function (request, error) {
                    console.log("Request: " + JSON.stringify(request));
                }
            });
        }


        $('.select_class').on('change', function () {
            var selectedClass = $(this).val();
            $("#getvalue").val(0);
            if (selectedClass) {
                loadData();
            } else {
                $('#loadData2').show();
                $('#loadData').empty();
            }
        });

        
    </script>

@endsection

