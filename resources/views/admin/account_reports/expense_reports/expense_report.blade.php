@extends('admin.layouts.main')


@inject('request', 'Illuminate\Http\Request')

@section('content')
    <div class="box box-primary">
        <style type="text/css">
            @page {
                margin: 10px 20px;
            }

            @media print {
                table {
                    font-size: 12px;
                }

                .tr-root-group {
                    background-color: #F3F3F3;
                    color: rgba(0, 0, 0, 0.98);
                    font-weight: bold;
                }

                .tr-group {
                    font-weight: bold;
                }

                .bold-text {
                    font-weight: bold;
                }

                .error-text {
                    font-weight: bold;
                    color: #FF0000;
                }

                .ok-text {
                    color: #006400;
                }
            }
        </style>
        <div class="panel-body pad table-responsive">
            <!--header--->

            <div class="row">
                <div class="col-md-12">
                    @if(isset($start_date) && isset($end_date))         <h6>Start Date : {!! $start_date !!} and End
                        Date : {!! $end_date !!}   </h6> @endif
                </div>

                <div class="col-md-12">
                    Today date {!!       date('Y-m-d'); !!}
                </div>
            </div>

            <!--end-header--->
            <table align="center">
                <tbody>
                <tr>
                    <td><h3 align="center"><span style="border-bottom: double;">Expense Summary Report</span></h3>
                    </td>
                </tr>
                <tr>
                    <td align="center"><span>As on </span>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="clear clearfix"></div>
            <!-- Liabilities and Assets -->

            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="th-style">Expenses (Dr)</th>
                        <th class="th-style" width="20%" style="text-align: right;">Amount (PKR)</th>
                    </tr>
                    </thead>
                    <tbody>{!! $expData !!}</tbody>
                </table>
            </div>
@endsection
