@extends('admin.layouts.main')

@section('title')
Component Create
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create Component</h3>
                    <div class="row    mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{{ route('exam.components.index') }}" class="btn btn-primary btn-md">
                                Back </a>
                        </div>
                    </div>

                    <form action="{!! route('exam.components.store') !!}" enctype="multipart/form-data"
                        id="form_validation" autocomplete="off" method="post">
                        @csrf
                        <div class="row mt-3">
                            <div class="col md-3">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>

                            <div class="col-md-3">
                                <label for="branches"><b>Company:</b></label>
                                <select name="company_id" class="form-select select2 basic-single mt-3"
                                    id="companySelect" aria-label=".form-select-lg example" required>
                                    @foreach($companies as $item)
                                    <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="Company-name"> <b> Academic Session </b></label>
                                <select name="session_id" class="form-select select2 basic-single mt-3 session_select"
                                    aria-label=".form-select-lg example" required>
                                    <option value="" disabled selected>Select Session</option>
                                    @foreach($sessions as $key => $item)
                                    <option value="{{$key}}">{{$item}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="branches"><b>Branch: </b></label>
                                <select name="branch_id" class="form-select select2 basic-single mt-3 branch_select"
                                    aria-label=".form-select-lg example" required>

                                </select>
                            </div>

                        </div>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <label for="branches"><b>Class: *</b></label>
                                <select required name="class_id"
                                    class="form-select select2 basic-single mt-3 select_class"
                                    aria-label=".form-select-lg example" required>

                                </select>
                            </div>


                            <div class="col-md-4">
                                <label for="branches"><b>Section: *</b></label>
                                <select required name="section_id"
                                    class="form-select select2 basic-single mt-3 select_section"
                                    aria-label=".form-select-lg example" >
                                    <option value="" disabled selected>Select Section</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="branches"><b>Subject: *</b></label>
                                <select required name="subject_id"
                                    class="form-select select2 basic-single mt-3 select_course"
                                    aria-label=".form-select-lg example">
                                    <option value="" selected disabled>Select Subject</option>
                                </select>
                            </div>


                        </div>

                        <div class="row mt-5">
                            <div id="loadData"></div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary"
                                style="margin-bottom: 10px;margin-left: 10px;">Save</button>
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
                        sectionDropdown.append('<option value="" selected disabled>Select Section</option>');

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
                // alert(class_id);
                $.ajax({
                    type: 'GET',
                    url: '{{ route("academic.fetchSubject") }}',
                    data: {
                        id: class_id
                    },
                    success: function (data) {
                        var classSubjectDropdown = $('.select_course').empty();
                        classSubjectDropdown.append('<option value="" selected disabled>Select Subject</option>');

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
    function loadData(){
            // var branch_id = $('#selectBranch').val();
            var subject_id = $('.select_course').val();
            var loader = $('<div class="loader"></div>').appendTo('body');

            $.ajax({

                url: "{{route('exam.component.data')}}",
                type: 'POST',
                data: {
                    'subject_id': subject_id,
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
        $(document).ready(function() {
            $('.select_course').on('change', function() {
                loadData();
            });
        });

</script>





@endsection 