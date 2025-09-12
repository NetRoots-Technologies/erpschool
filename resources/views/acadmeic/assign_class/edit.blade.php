@extends('admin.layouts.main')

@section('title')
    Assign Class
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Update Class</h3>
                        <div class="row mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.assign_class.index') !!}"
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
                        <form action="{!! route('academic.assign_class.update',$assignClass->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                           @method('put')
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">
                                    <div class="row mt-3">

                                        <div class="row mt-3">

                                            <div class="col-md-4">
                                                <label for="branches"><b>Company:</b></label>
                                                <select  name="company_id"
                                                         class="form-select select2 basic-single mt-3" id="companySelect"
                                                         aria-label=".form-select-lg example" required>
                                                    <option value="">Select Company</option>
                                                    @foreach($companies as $item)
                                                        <option  value="{{$item->id}}" {!! $assignClass->company_id == $item->id ? 'selected' : '' !!}>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="Company-name"> <b> Academic Session </b></label>
                                                <select  name="session_id"
                                                         class="form-select select2 basic-single mt-3" id="session_id"
                                                         aria-label=".form-select-lg example" required>
                                                    <option value="">Select Session</option>
                                                    @foreach($sessions as $key => $item)
                                                        <option value="{!! $key !!}"  {!! $assignClass->session_id == $key ? 'selected' : '' !!}>{!! $item !!}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="branches"><b>Branch: </b></label>
                                                <select  name="branch_id"
                                                         class="form-select select2 basic-single mt-3 branch_select"
                                                         aria-label=".form-select-lg example" required>

                                                </select>
                                            </div>

                                        </div>

                                        <div class="row mt-4">

                                            <div class="col-md-4">
                                                <label for="branches"><b>Class:</b></label>
                                                <select required name="class_id"
                                                        class="form-select select2 basic-single mt-3 select_class"
                                                        aria-label=".form-select-lg example">

                                                </select>
                                            </div>


                                            <div class="col-md-4">
                                                <label for="branches"><b>Section:</b></label>
                                                <select required name="section_id"
                                                        class="form-select select2 basic-single mt-3 select_section"
                                                        aria-label=".form-select-lg example">
                                                    <option value="">Select Section</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="branches"><b>Students </b></label>
                                                <select  name="student_id"
                                                         class="form-select select2 basic-single mt-3"
                                                         aria-label=".form-select-lg example" required>
                                                    <option value="">Select Students</option>
                                                    @foreach($students as $student)
                                                        <option value="{{$student->id}}"  {!! $assignClass->student_id == $student->id ? 'selected' : '' !!} >{{$student->first_name. ' ' .$student->last_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div style="margin-top: 20px">
                                            <button type="submit" class="btn btn-primary">Submit</button>
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
                        branchesDropdown.append('<option value="" disabled>Select Branch</option>');

                        data.forEach(function (branch) {

                            var selectedBranch = branch.id == '{{ $assignClass->branch_id }}' ? 'selected' : '';

                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectedBranch + '>' + branch.name + '</option>');

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
    var branch_id;
        $(document).ready(function () {

            $('.branch_select').on('change', function () {
                 branch_id = $(this).val();
                 if (branch_id == null){
                     branch_id = {!! $assignClass->branch_id  !!}
                 }

                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var classDropdown = $('.select_class').empty();

                        classDropdown.append('<option value="">Select Class</option>');

                        data.forEach(function (academic_class) {

                            var selectedClass = academic_class.id == '{{ $assignClass->class_id }}' ? 'selected' : '';
                            classDropdown.append('<option value="' + academic_class.id + '" ' + selectedClass + '>' + academic_class.name + '</option>');
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
                if (class_id == null){
                    class_id = {!! $assignClass->class_id  !!}
                }
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

                            var selectedSection = section.id == '{{ $assignClass->section_id }}' ? 'selected' : '';
                            sectionDropdown.append('<option value="' + section.id + '" ' + selectedSection + '>' + section.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        })
    </script>





@endsection

