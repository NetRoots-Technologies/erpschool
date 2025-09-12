@extends('admin.layouts.main')

@section('title')
Bank Accounts Create
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Bank Account</h3>
                    <div class="row    mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{!! route('admin.banks_accounts.index') !!}" class="btn btn-primary btn-md">
                                Back </a>
                        </div>
                    </div>

                    <form action="{!! route('admin.banks_accounts.update',$bankAccount->id) !!}"
                        enctype="multipart/form-data" id="form_validation" autocomplete="off" method="post">
                        @csrf
                        {{method_field('PUT')}}
                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:20px;">
                                <div class="row">

                                    <div class="col-md-4">
                                        <label for="Academic"><b>Bank </b></label>
                                        <select name="bank_id" class="form-control select2 basic-single bank">
                                            <option>Select Bank</option>
                                            @foreach($banks as $item)
                                            <option value="{!! $item->id !!}" {!! $bankAccount->bank_id == $item->id ?
                                                'selected' : '' !!}>{!! $item->name !!}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="branches"><b>Bank Branch </b></label>
                                        <select name="bank_branch_id" required
                                            class="form-control  select2 basic-single bank_branch" id="companySelect">
                                            <option value="" selected disabled>Select Branch</option>

                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="branches"><b>Account Type *</b></label>
                                        <select
                                            class="form-control select2 basic-single" required
                                            id="type" name="type">
                                            <option value="MOA" {{$item->type == "MOA" ? "selected" : "" }} >MOA - Money Operational Account</option>
                                            <option value="MCA" {{$item->type == "MCA" ? "selected" : "" }} >MCA - Money Collection Account</option>
                                        </select>
                                    </div>


                                    <div class="col-md-4">
                                        <label for="branches"><b>Account No </b></label>
                                        <input type="text" class="form-control" value="{!! $bankAccount->account_no !!}"
                                            name="bank_account_no" required>
                                    </div>

                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>

                    </form>
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
        var bank_id;
                $(document).ready(function () {
                    $('.bank').on('change', function () {
                        bank_id = $(this).val();
                        console.log(bank_id);
                        if (bank_id == null) {
                            bank_id = {!! $bankAccount->bank_id !!};
                        }
                        $.ajax({
                            type: 'GET',
                            url: '{{ route('admin.fetch.bankBranches') }}',
                            data: {
                                bank_id: bank_id
                            },
                            success: function (data) {
                                var branchesDropdown = $('.bank_branch').empty();

                                branchesDropdown.append('<option value="">Select Branch</option>');

                                data.forEach(function (branch) {
                                    var selectedClass = branch.id == '{{ $bankAccount->bank_branch_id }}' ? 'selected' : '';
                                    branchesDropdown.append('<option value="' + branch.id + '" ' + selectedClass + '>' + branch.branch_name + '</option>');
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
