@extends('admin.layouts.main')

@section('title')
{{$type}}
@stop

@section('content')
<div class="container-fluid">
    <div class="row w-100">
        <input type="hidden" id="type" class="form-control" value="{{$type}}" required>
        <div class="card p-4 shadow-sm w-100">
            <div class="row mt-3">
                <div class="col-4">
                    <label for="year" class="form-label"><b>Year*</b></label>
                    <input type="year" id="year" class="form-control" value="{{$currentYear}}" required>
                </div>
                <div class="col-4">
                    <label for="month" class="form-label"><b>Month</b></label>
                    <input type="month" id="month" class="form-control" value="{{$currentYear}}" required>
                </div>
                <div class="col-4">
                    <label for="employees" class="form-label"><b>Employees</b></label>
                    <select name="employees" id="employees_list" class="form-control">
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                

            </div>
        </div>
    </div>

    <div class="row w-100">
        <div class="col-12">
            <div class="card basic-form shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped mb-0" id="data_table">
                    </table>
                    <div class="card">
                        <div class="card-header">
                            <h5>Total Contributions</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Total Contribution</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Company Contribution</td>
                                        <td><span id="total_company_contribution">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>Employee Contribution</td>
                                        <td><span id="total_employee_contribution">0.00</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td><span id="total">0.00</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script defer>
    $(document).ready(function(){
            'use strict';

            // $('#employees_list').select2({
            //     // placeholder: "Select Employees",
            //     // allowClear: true,
            // });

            const uri = @json(route('hr.employeeBenefit.show'))

            let dt = $('#data_table').DataTable({
                ajax: {
                    url: uri,
                    type: 'POST',             
                    dataSrc: function (json) {
                        return json;
                    },
                    data: function (d) {
                        d.year = $('#year').val();
                        d.type = $('#type').val();
                        d.month = $('#month').val();
                        d.employee_id = $('#employees_list').val(); ;
                        
                    },
                    beforeSend: function (xhr) {
                        var token = $('meta[name="csrf-token"]').attr('content');
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
                columns: [
                    // { data: 'id', title: 'Sr No' , width: "7%"},  
                    { data: null, title: 'Sr No', width: "7%", orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'employee.name', title: 'Name' },
                    { data: 'company_amount', title: 'Company Contribution' },
                    { data: 'employee_amount', title: 'Employee Contribution' },
                    { data: 'month_name', title: 'Month' },  
                    { data: 'year', title: 'Year' },
                ],
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                language: {
                    emptyTable: 'No data available in the table.'
                },
                drawCallback: function(settings) {
                    let api = this.api();
                    let totalCompanyContribution = 0;
                    let totalEmployeeContribution = 0;

                    api.rows({ page: 'current' }).every(function() {
                        let data = this.data();
                        totalCompanyContribution += parseFloat(data.company_amount) || 0;
                        totalEmployeeContribution += parseFloat(data.employee_amount) || 0;
                    });

                    $('#total_company_contribution').text(totalCompanyContribution.toFixed(2));
                    $('#total_employee_contribution').text(totalEmployeeContribution.toFixed(2));
                    $('#total').text(totalEmployeeContribution + totalCompanyContribution);
                }
            });

            $('#year').on('keyup', function(e){
                dt.ajax.reload();
            })

            $('#month').on('change', function(e){
                dt.ajax.reload();
            })
            $('#employees_list').on('change', function() {
                var selectedEmployeeId = $(this).val();
                dt.ajax.reload();
            });
        })
</script>
@endsection