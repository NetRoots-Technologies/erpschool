@extends('admin.layouts.main')

@section('title', 'Fee Bills in All Students')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <div class="page-leftheader"></div>
                    <h4 class="page-title mb-0">Fee Bills in All Students</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.index') }}">Fee Management</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.fee-management.reports') }}">Reports</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Fee Bills in All Students</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Options</h3>
                </div>
                <div class="card-body">
                    <div class="row">


                        <div class="col-md-12">
                           <form action="{{ route('admin.fee-management.reports.month-download') }}" method="GET">
                                <div class="row g-2 align-items-end my-3">
                                    <div class="col-md-3">
                                        <label for="filter_month" class="form-label">Select Month</label>
                                        <input type="month" class="form-control" name="filter_month" id="filter_month" value="{{ date('Y-m') }}">
                                    </div>
                                    <div class="col-md-7">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-file-earmark-pdf"></i> Download All Bills ZIP
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        

                </div>
            </div>
        </div>

     

    @endsection

   @section('js')
<script>
    $(document).ready(function() {
        $('#resetFilters').on('click', function() {
            $('#filter_month').val('');
           
        });
    });
</script>
@endsection

