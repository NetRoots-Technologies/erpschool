@extends('admin.layouts.main')

@section('title', 'Approval Authorities')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        @if (Gate::allows('SignatoryAuthorities-create'))
        <div class="col-12 text-right">
            <a href="{{ route('admin.signatory-authorities.add') }}" class="btn btn-success">Add Authority</a>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h4>Approval Authorities List</h4>
        </div>
        <div class="card-body">
            <table id="approval-authorities-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>User Name</th>
                        <th>Module</th>
                        <th>Company</th>
                        <th>Branch</th>
                        <th>Status</th>
          
                    </tr>
                </thead>
                <tbody>
                    {{-- DataTables will populate this --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('#approval-authorities-table').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'collection',
                    text: 'Export',
                    buttons: ['excel', 'pdf', 'print'],
                    className: 'btn btn-dark'
                },
                {
                    extend: 'colvis',
                    text: 'Column Visibility',
                    className: 'btn btn-light'
                }
            ],
            ajax: '{{ route("admin.signatory-authorities.get") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'role', name: 'role' },
                { data: 'user', name: 'user' },
                { data: 'module', name: 'module' },
                { data: 'company', name: 'company' },
                { data: 'branch', name: 'branch' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
              
            ]
        });
    });
</script>
@endsection
