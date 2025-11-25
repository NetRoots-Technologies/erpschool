@extends('admin.layouts.main')

@section('title', 'Family Profile Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h4 class="page-title mb-0">Family Profile Report</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Family Profile</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Filters -->
    {{-- <div class="row filter-card">
        <div class="col-md-3">
            <div class="form-group">
                <label for="class_id">Class</label>
                <select class="form-control select2" id="class_id">
                    <option value="">--Select Class--</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

       

        <div class="col-md-3 align-self-end">
            <button type="button" id="resetFilters" class="btn btn-info btn-sm">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </button>
        </div>
    </div> --}}

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <table id="familyFeeOutstandingTable" class="table table-striped table-bordered table-hover w-100">
                        <thead>
                            <tr>
                                {{-- <th>Student Name</th> --}}
                                <th>Date of Joining</th>
                                <th>Father Name</th>
                                <th>Family ID</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Postal Address</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
              </div>
        </div>
    </div>
</div>
@endsection


@section('css')
<style>
    .card-header { background-color: #0d6efd; color: white; }
    .table thead th { background-color: #e9ecef; }
    .filter-card { border-radius: 15px; background: #f8f9fa; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
</style>
@endsection

    @section('js')
        <script>
            $("document").ready(function() {
                var table = $('.table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                       url: '{{ route("admin.fee-management.reports.family-profile") }}',
                        data: function(d) {
                            // d.status = $('#status').val();
                           
                        }
                    },

                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'copy',
                            text: 'Copy'
                        },
                        {
                            extend: 'csv',
                            text: 'CSV'
                        },
                        {
                            extend: 'excel',
                            text: 'Excel'
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF'
                        },
                        {
                            extend: 'print',
                            text: 'Print'
                        }
                    ],

                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ],


                        columns: [
                        // { data: 'student_name', name: 'student_name',searchable: true, orderable: true },
                        { data: 'admission_date', name: 'admission_date',searchable: true, orderable: true },
                        { data: 'father_name', name: 'father_name',searchable: true, orderable: true },
                        { data: 'father_cnic', name: 'father_cnic',searchable: true, orderable: true },
                        { data: 'cell_no',name: 'cell_no',searchable: true, orderable: true },
                        { data: 'student_email', name: 'student_email',searchable: true, orderable: true },
                        { data: 'student_permanent_address',name: 'student_permanent_address',searchable: true, orderable: true }
                        ,
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
     
    
                });

                
            });
        </script>
    @endsection

