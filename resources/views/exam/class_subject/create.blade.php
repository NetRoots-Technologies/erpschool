@extends('admin.layouts.main')

@section('title')
class Subject Create
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create class Subject</h3>
                    <div class="row mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{!! route('exam.class_subjects.index') !!}" class="btn btn-primary btn-md">
                                Back </a>
                        </div>
                    </div>

                    <form action="{!! route('exam.class_subjects.store') !!}" enctype="multipart/form-data"
                        id="form_validation" autocomplete="off" method="post">
                        @csrf
                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:20px;">
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label for="branches"><b>Company:</b></label>
                                        <select name="company_id" class="form-select select2 basic-single mt-3"
                                            id="companySelect" aria-label=".form-select-lg example">
                                            @foreach($companies as $item)
                                            <option selected value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="Company-name"> <b> Academic Session </b></label>
                                        <select name="session_id" class="form-select select2 basic-single mt-3 "
                                            aria-label=".form-select-lg example">
                                            <option value="">Select Session</option>
                                            @foreach($sessions as $key => $item)
                                            <option value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="branches"><b>Branch: </b></label>
                                        <select name="branch_id" 
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
                                        <div class="input-label">
                                            <label class="branch_Style"><b>Subject*</b></label>
                                        </div>
                                        <select name="subject_id" class="form-control select2 basic-single select_subject" required>
                                            <option value="">Select Subject</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="abbr">
                                                Compulsory
                                            </label>
                                            <br>
                                            <input type="checkbox" name="compulsory">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="acd">
                                                Acd
                                            </label>
                                            <br>
                                            <input type="checkbox" name="acd">
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <label for="abbr">
                                            Acd Sort
                                        </label>
                                        <br>
                                        <input type="text" class="form-control" name="acd_sort">
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="abbr">
                                                Skill
                                            </label>
                                            <br>
                                            <input type="checkbox" name="skill">
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <label for="skill">
                                            Skill Sort
                                        </label>
                                        <br>
                                        <input type="text" class="form-control" name="skill_sort">
                                    </div>
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
                        branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                },
                complete: function () {
                    loader('hide');
                }
            });
        }).change();

        $('.branch_select').on('change', function () {
            loader('show');
            var branch_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetchClass') }}',
                data: {
                    branch_id: branch_id
                },
                success: function (data) {
                    var sectionDropdown = $('.select_class').empty();
                    sectionDropdown.append('<option value="">Select Class</option>');
                    data.forEach(function (academic_class) {
                        sectionDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching classes:', error);
                },
                complete: function () {
                    loader('hide');
                }
            });
        });

        $('.select_class').on('change', function () {
            loader('show');
            var class_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetchSubject') }}',
                data: {
                    class_id: class_id
                },
                success: function (data) {
                    var subjectDropdown = $('.select_subject').empty();
                    subjectDropdown.append('<option value="">Select Subject</option>');
                    data.forEach(function (subject) {
                        subjectDropdown.append('<option value="' + subject.id + '">' + subject.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching subjects:', error);
                },
                complete: function () {
                    loader('hide');
                }
            });
        });

        $('input[type="checkbox"]').change(function() {
            this.value = this.checked ? '1' : '0';
        });
    });
</script>
@endsection