@extends('admin.layouts.main')

@section('title')
    Edit Subject Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit class Subject</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('exam.class_subjects.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('exam.class_subjects.update',$classSubject->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            {{method_field('put')}}
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:20px;">


                                    <div class="row mt-3">

                                        <div class="col-md-4">
                                            <label for="branches"><b>Company:</b></label>
                                            <select name="company_id"
                                                    class="form-select select2 basic-single mt-3" id="companySelect"
                                                    aria-label=".form-select-lg example">
                                                @foreach($companies as $item)
                                                    <option selected value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="Company-name"> <b> Academic Session </b></label>
                                            <select name="session_id"
                                                    class="form-select select2 basic-single mt-3 "
                                                    aria-label=".form-select-lg example">
                                                <option value="">Select Session</option>
                                                @foreach($sessions as $key => $item)
                                                    <option value="{{$key}}" {!! $classSubject->session_id == $key ? 'selected' : ''  !!}>{{$item}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Branch: </b></label>
                                            <select name="branch_id"
                                                    class="form-select select2 basic-single mt-3 branch_select"
                                                    aria-label=".form-select-lg example">

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
                                            <select name="subject_id"
                                                    class="form-control  select2 basic-single "
                                                    required>
                                                <option selected>Select Subject</option>
                                                @foreach($Subjects as $item)
                                                    <option value="{!! $item->id !!}" {!! $classSubject->subject_id == $item->id ? 'selected' : ''  !!}>{!! $item->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="compulsory">
                                                    Compulsory
                                                </label>
                                                <br>
                                                <input type="checkbox" name="compulsory" value="1" {{ $classSubject->compulsory == 1 ? 'checked' : '' }}>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row mt-4 mb-4">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="acd">
                                                    Acd
                                                </label>
                                                <br>
                                                <input type="checkbox" name="acd" {{ $classSubject->acd == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="abbr">
                                                Acd Sort
                                            </label>
                                            <br>
                                            <input type="text" name="acd_sort" class="form-control" value="{{ $classSubject->acd_sort }}">
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="abbr">
                                                    Skill
                                                </label>
                                                <br>
                                                <input type="checkbox" name="skill" {{ $classSubject->skill == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="skill">
                                                Skill Sort
                                            </label>
                                            <br>
                                            <input type="text" name="skill_sort" class="form-control" value="{{ $classSubject->skill_sort }}">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update</button>
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
                            var selectedbranches = branch.id == '{{ $classSubject->branch_id  }}' ? 'selected' : '';
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
                    branch_id = {!! $classSubject->branch_id !!}
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

                            var selectedclass = academic_class.id == '{{ $classSubject->class_id }}' ? 'selected' : '';
                            classDropdown.append('<option value="' + academic_class.id + '" ' + selectedclass + '>' + academic_class.name + '</option>');
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
        $(document).ready(function () {
            $('input[type="checkbox"]').change(function () {
                this.value = this.checked ? '1' : '0';
            });
        });
    </script>

@endsection

