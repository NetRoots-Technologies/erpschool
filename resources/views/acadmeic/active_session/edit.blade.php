@extends('admin.layouts.main')

@section('title')
    Active Session | Edit
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit Active Session</h3>
                        <div class="row mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('academic.active_sessions.index') !!}" class="btn btn-primary btn-md">
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
                        <form action="{!! route('academic.active_sessions.update',$activeSession->id) !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')
                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:10px;">
                                    <div class="row mt-3">

                                        <div class="row mt-3">

                                            <div class="col-md-6">
                                                <label for="branches"><b>Session:</b></label>
                                                <select  name="session_id"
                                                         class="form-select select2 basic-single mt-3" id="session_id"
                                                         aria-label=".form-select-lg example" required>
                                                    <option value="">Select Session</option>

                                                    @foreach($sessions as $key => $item)
                                                        <option value="{{$key}} " {!!  $activeSession->session_id == $key ? 'selected' : '' !!}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="branches"><b>Company: </b></label>
                                                <select  name="company_id"
                                                        class="form-select select2 basic-single mt-3" id="companySelect"
                                                        aria-label=".form-select-lg example" required>
                                                    <option value="">Select Company</option>
                                                    @foreach($companies as $item)
                                                        <option value="{{$item->id}}" {!! $activeSession->company_id == $item->id ? 'selected' : '' !!}>{{$item->name}}</option>
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
                                                <label for="branches"><b>Class: </b></label>
                                                <select  name="class_id"
                                                        class="form-select select2 basic-single mt-3 select_class"
                                                        aria-label=".form-select-lg example" required>

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
                            var selectBranch = branch.id == '{{ $activeSession->branch_id }}' ? 'selected' : '';

                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectBranch + '>' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


            $('.branch_select').on('change', function () {

                branch_id = $(this).val();
                if(branch_id == null){
                    branch_id = {!! $activeSession->branch_id !!}
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.select_class').empty();

                        data.forEach(function (academic_class) {
                            var selectsection = academic_class.id == '{{ $activeSession->class_id }}' ? 'selected' : '';

                            sectionDropdown.append('<option value="' + academic_class.id + '" ' + selectsection + '>' + academic_class.name + '</option>');
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

