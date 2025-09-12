
@inject('helper', 'App\Helper\helper')
@extends('admin.layouts.main')


@section('breadcrumbs')

@stop

@section('content')
    <section class="content-header" style="padding: 10px 15px !important;">
        <h1>Bank Accounts</h1>
        @if(Session::get('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{Session::get("status")}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </section>
    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"></div>
                        @if (Gate::allows('students'))
                        <div class="col-md-4">
                            <a class="btn btn-success mb-3 float-end" href="/admin/BankAccountDetail/create" role="button">Add Account</a>
                        </div>
                        @endif
                    </div>
                    <table  class="table table-bordered bank_account_details-table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Bank Name</th>
                            <th scope="col">Branch Code</th>
                            <th scope="col">Account Title</th>
                            <th scope="col">Account No</th>
                            <th scope="col">Account Type</th>
                            <th scope="col">Phone No</th>
                            <th scope="col">Address</th>
                            <th scope="col">Action</th>

                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function () {
            var table = $('.bank_account_details-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('BankAccountDetail.getData') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'bank_name', name: 'bank_name'},
                    {data: 'branch_code_id', name: 'branch_code_id'},
                    {data: 'account_title', name: 'account_title'},
                    {data: 'account_no', name: 'account_no'},
                    {data: 'account_type', name: 'account_type'},
                    {data: 'phone_no', name: 'phone_no'},
                    {data: 'address', name: 'address'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });
    </script>

@endsection



