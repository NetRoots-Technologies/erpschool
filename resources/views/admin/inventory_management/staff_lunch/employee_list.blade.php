@extends('admin.layouts.main')

@section('title')
Served Employees
@stop

@section('content')

<div class="container-fluid">

    <a href="{{ route('inventory.staff_lunch.emp_view') }}" class="btn btn-primary my-3">Back</a>
    <div class="row justify-content-center my-4">
        <div class="col-12">
            <div class="card basic-form shadow-sm">
                <div class="card-body table-responsive">
                    <h2 class="text-center">List of Served Employees</h2>

                    <div class="row font-weight-bold border-bottom mt-5 pb-2 text-center">
                        <div class="col-md-4 fs-5">Employee Name</div>
                        <div class="col-md-4 fs-5">Meal</div>
                        <div class="col-md-4 fs-5">Served</div>
                    </div>

                    @foreach ($employee_batch_products as $employee_batch_product)
                    <div class="row py-2 border-bottom text-center">
                        <div class="col-md-4">{{ $employee_batch_product->employee->name }} </div>
                        <div class="col-md-4">{{ $employee_batch_product->product->name }}</div>
                        <div class="col-md-4 {{ $employee_batch_product->assigned == 1 ? 'text-success' : 'text-danger' }}">
                            <i class="fa fa-{{ $employee_batch_product->assigned == 1 ? 'check' : 'times' }}"></i>
                        </div>
                    </div>
                @endforeach


                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
<script>
  $(document).ready(function () {
    'use strict';

});

</script>
@endsection
