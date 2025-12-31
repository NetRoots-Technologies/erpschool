@extends('admin.layouts.main')

@section('title', 'Student Challans ')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-3">

    <div class="row mb-2">
        <div class="col-12">
            <div class="page-header">
                <div class="page-leftheader"></div>
                <h4 class="page-title mb-0">Challans for {{ $studentDatabank->first_name }} {{ $studentDatabank->last_name }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('academic.studentDataBank.index') }}">Pre Admission</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Challans</li>
                </ol>  
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <table id="challansTable" class="table table-bordered table-striped" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Challan No</th>
                        <th>Reference No</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Paid Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentDatabank->challans as $challan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $challan->challan_no }}</td>
                        <td>{{ $challan->reference_no }}</td>
                        <td>{{ number_format($challan->amount, 2) }}</td>
                        <td>{{ ucfirst($challan->status) }}</td>
                        <td>{{ $challan->issue_date }}</td>
                        <td>{{ $challan->due_date ?? '-' }}</td>
                        <td>{{ $challan->paid_date ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

</div>

@endsection

{{-- ================== JS SECTION ================== --}}
@section('js')
<script>
$(function () {
    $('#challansTable').DataTable({
        "pageLength": 10,
        "ordering": true,
        "searching": true,
        "responsive": true
    });
});
</script>
@endsection
