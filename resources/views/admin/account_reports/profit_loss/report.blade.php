@inject('request', 'Illuminate\Http\Request')

<div class="row">
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

            .table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {
                padding: 2px !important;
            }
        }
    </style>
    <div class="panel-body pad table-responsive">
        <div class="panel-body pad table-responsive">
            <div class="col-md-6">
                <h4>
                    Profit & Loss Report</h4>
            </div>
            @if($request->get('medium_type') == 'web')
            <div class="col-md-6">
                <div class="text-center pull-right">
                    <button onclick="loadReport(`print`);" type="button" class="btn btn-flat"><i
                            class="fa fa-print"></i>&nbsp;Print</button>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th class="th-style">Incomes (Cr)</th>
                        <th class="th-style" width="20%" style="text-align: right;">Amount (Pkr)</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- {!! $expData !!} --}}
                    {!! $incomeData !!}


                    <tr class="bold-text">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>

            <!-- Liabilities -->
            <table class="table">
                <thead>
                    <tr>
                        <th class="th-style">Expenses (Dr)</th>
                        <th class="th-style" width="20%" style="text-align: right;">Amount (Pkr)</th>
                    </tr>
                </thead>
                <tbody>
                    {!! $expData !!}
                    {{-- {!! $incomeData !!} --}}


                </tbody>
            </table>
        </div>
    </div>

</div>
