@extends('admin.layouts.main')

@section('title', 'Fee Management Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0">Fee Management Dashboard</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Fee Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-primary text-white me-3">
                            <i class="fa fa-tags"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Total Categories</h6>
                            <h3 class="mb-0">{{ $data['total_categories'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-success text-white me-3">
                            <i class="fa fa-sitemap"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Fee Structures</h6>
                            <h3 class="mb-0">{{ $data['total_structures'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-info text-white me-3">
                            <i class="fa fa-money"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Total Collected</h6>
                            <h3 class="mb-0">Rs. {{ number_format($data['total_collections']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-warning text-white me-3">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Pending Amount</h6>
                            <h3 class="mb-0">Rs. {{ number_format($data['pending_amount']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.fee-management.categories.create') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-plus"></i> Add Fee Category
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.fee-management.structures.create') }}" class="btn btn-success btn-block">
                                <i class="fa fa-sitemap"></i> Create Fee Structure
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.fee-management.collections.create') }}" class="btn btn-info btn-block">
                                <i class="fa fa-money"></i> Record Collection
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('admin.fee-management.billing') }}" class="btn btn-warning btn-block">
                                <i class="fa fa-file-text"></i> Generate Billing
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Collections -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Fee Collections</h3>
                    <div class="card-options">
                        <a href="{{ route('admin.fee-management.collections') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center">No recent collections found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Dashboard initialization
        console.log('Fee Management Dashboard loaded');
    });
</script>
@endsection
