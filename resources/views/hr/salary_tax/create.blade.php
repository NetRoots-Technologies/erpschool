@extends('admin.layouts.main')

@section('title')
    Salary Tax Create
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Salary Tax</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.salary-tax.index') !!}" class="btn btn-primary btn-sm "> Back </a>
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
                        <form action="{!! route('hr.tax-slabs.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            <div class="w-100 p-3">
                                <div class="box-body">

                                    <button type="button" id="add-row-btn" class="btn btn-primary">Add Row</button>
                                    <table class="table table-bordered table-striped" style="margin-top: 10px">
                                        <thead>
                                        <tr>
                                            <th><b>Tax Class</b></th>
                                            <th><b>Fix Amount</b></th>
                                            <th><b>Tax Percent</b></th>
                                            <th><b>Start Range</b></th>
                                            <th><b>End Range</b></th>
                                            <th><b>Actions</b></th>
                                        </tr>
                                        </thead>
                                        <tbody id="table-body">
                                        <input type="hidden" name="section_name" value="salary-tax">
                                        @foreach($taxes as $tax)
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="tax_type[]" value="salaryTax">
                                                    <select class="form-control" required name="financial_year_id[]">
                                                        @foreach($formattedFinancialYears as $id => $formattedFinancialYear)
                                                            <option value="{{ $id }}" {{ $tax->tax_class == $id ? 'selected' : '' }}>{{ $formattedFinancialYear }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="number" class="form-control"  name="fix_amount[]" value="{{ $tax->fix_amount }}"></td>
                                                <td><input type="text" class="form-control" required name="tax_percent[]" value="{{ $tax->tax_percent }}"></td>
                                                <td><input type="number" class="form-control" required name="start_range[]" value="{{ $tax->start_range }}"></td>
                                                <td><input type="number" class="form-control" required name="end_range[]" value="{{ $tax->end_range }}"></td>
                                                <td>
                                                    <button type="button" class="btn btn-danger delete-row"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="6">
                                                <button type="submit" class="btn btn-primary" >Save</button>
                                            </td>
                                        </tr>
                                        </tfoot>
                                    </table>

                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function () {

            $('#add-row-btn').click(function () {
                var newRow = '<tr>' +
                    '<input type="hidden" name="tax_type[]" value="salaryTax">' +
                    '<td>' +
                    '<select class="form-control" required name="financial_year_id[]">' +
                    '@foreach($formattedFinancialYears as $id => $formattedFinancialYear)' +
                    '<option value="{{ $id }}">{{ $formattedFinancialYear }}</option>' +
                    '@endforeach' +
                    '</select>' +
                    '</td>' +
                    '<td><input type="number"  class="form-control" name="fix_amount[]"></td>' +
                    '<td><input type="text" required class="form-control" name="tax_percent[]"></td>' +
                    '<td><input type="number" required class="form-control" name="start_range[]"></td>' +
                    '<td><input type="number" required class="form-control" name="end_range[]"></td>' +
                    '<td><button type="button" class="btn btn-danger delete-row"><i class="fas fa-trash"></i></button></td>' +
                    '</tr>';

                $('#table-body').append(newRow);
            });



            $(document).on('click', '.delete-row', function () {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
