@extends('admin.layouts.main')

@section('title')
    {{ isset($employee) ? 'Update' : 'Create' }} Employee Bank Detail
@stop

@section('content')
@php
      
    $salary = \App\Helpers\GeneralSettingsHelper::getSetting('salaryValue');
        if ($salary != null){
            $salaryValue = floatval($salary['value']);
        }
        else{
        $salaryValue = 0;
    }

@endphp
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4">
                        {{ isset($employee) ? 'Update' : 'Create' }} Employee Bank Detail
                    </h3>

                    <div class="row mt-4 mb-4">
                        <div class="col-12 text-right">
                            <a href="{{ route('hr.employee.index') }}" class="btn btn-primary btn-md">Back</a>
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

                    <form action="{{ route('hr.employee.bank.save', $employee->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="bank_name">Bank Name</label>
                                <select name="bank_name" class="form-control select2" required>
                                    <option value="">Select Bank</option>
                                    @foreach($pakistaniBanks as $bank)
                                        <option value="{{ $bank }}" {{ old('bank_name', $employee->bank_name) === $bank ? 'selected' : '' }}>
                                            {{ $bank }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="account_number">Account Number</label>
                                <input type="text" name="account_number" id="account_number"
                                       class="form-control"
                                       value="{{ old('account_number', $employee->account_number) }}"
                                       required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="grossSalary">Gross Salary *</label>
                                <input type="number" name="grossSalary" class="form-control" id="gross_salary"
                                       value="{{ old('grossSalary', $employee->grossSalary) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="salary">Basic Salary:</label>
                                <input type="number" name="salary" class="form-control" id="basic_salary"
                                       value="{{ old('salary', $employee->salary) }}" readonly>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-success">
                                    {{ isset($employee) ? 'Update' : 'Create' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div> <!-- col -->
    </div> <!-- row -->
</div> <!-- container -->
    
@stop

@section('js')


    <script>
        $(document).ready(function () {
            $('#gross_salary').on('input', function () {
                 var grossSalary = parseFloat($(this).val());

                var salaryValue = {!! $salaryValue !!};
              
                var basicSalary = Math.floor(grossSalary / salaryValue);

                $('#basic_salary').val(basicSalary);
            });
        });
    </script>
@endsection
      

