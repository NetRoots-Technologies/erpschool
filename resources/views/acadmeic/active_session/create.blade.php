@extends('admin.layouts.main')

@section('title')
    Active Session | Create
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Active Session</h3>
                        <div class="row mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.active_sessions.index') !!}"
                                   class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('academic.active_sessions.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:30px;">
                                    <div class="row mt-3">

                                        <div class="row mt-3">

                                            <div class="col-md-6">
                                                <label for="branches"><b>Session:</b></label>
                                                <select  name="session_id"
                                                         class="form-select select2 basic-single mt-3" id="session_id"
                                                         aria-label=".form-select-lg example" required>
                                                    <option value="">Select Session</option>
                                                    @foreach($sessions as $key => $item)
                                                        <option value="{{$key}}">{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="branches"><b>Company:</b></label>
                                                <select  name="company_id"
                                                         class="form-select select2 basic-single mt-3" id="companySelect"
                                                         aria-label=".form-select-lg example" required>
                                                    <option value="">Select Company</option>
                                                    @foreach($companies as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        </div>

                                        <div class="row mt-4">

                                            <div class="col-md-6">
                                                <label for="branches"><b>Branch: </b></label>
                                                <select  name="branch_id"
                                                         class="form-select select2 basic-single mt-3 branch_select"
                                                         aria-label=".form-select-lg example" required>

                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="branches"><b>Class: *</b></label>
                                                <select required name="class_id"
                                                        class="form-select select2 basic-single mt-3 select_class"
                                                        aria-label=".form-select-lg example">

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


@endsection

