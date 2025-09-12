@inject('request', 'Illuminate\Http\Request')
@inject('CoreAccounts', 'App\Helpers\CoreAccounts')
@if($request->get('medium_type') != 'web')
    @if($request->get('medium_type') == 'pdf')
        @include('partials.pdf_head')
    @else
        @include('partials.head')
    @endif
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
@endif
@inject('CoreAccounts', '\App\Helpers\CoreAccounts')
<div class="panel-body pad table-responsive" style="max-height: 100%;">
    <div class="col-md-6">
        <h4>KARIGAR ACCOUNT REPORT</h4>
    </div>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>V.NO</th>
            <th>DATE</th>
            <th>DESCRIPTIONS</th>
            <th>ITEM NAME</th>
            <th>SHIFT TO</th>
            <th>GROSS WT</th>
            <th>DIA WT</th>
            <th>ST WT</th>
            <th>BEEDS</th>
            <th>NET WT</th>
            <th>WASTE/RATE</th>
            <th>TOTAL/WT</th>
            <th>PURITY</th>
            <th>MAKING</th>
            <th>OTHERS</th>
            <th>DAI $</th>
            <th>ST Rs</th>
            <th>BEEDS RS</th>
            <th style="padding: 0px; text-align: center">PURE WT
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>Dr</td>
                        <td>Cr</td>
                    </tr>
                </table>
            </th>
            <th style="padding: 0px; text-align: center">TOTAL PKR
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>Dr</td>
                        <td>Cr</td>
                    </tr>
                </table>
            </th>
            <th style="padding: 0px; text-align: center">CURRENCY
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>Dr</td>
                        <td>Cr</td>
                    </tr>
                </table>
            </th>
            <th>GOLD BAL</th>
            <th>PKR BAL</th>
            <th>CURRENCY BAL</th>
        </tr>
        </thead>
        <?php for($i=0; $i<5; $i++){ ?>
        <tr>
            <td>Gr-001</td>
            <td>12-02-2020</td>
            <td>test descriptons</td>
            <td>Ring</td>
            <td>Karigar</td>
            <td>10</td>
            <td>10</td>
            <td>10</td>
            <td>0</td>
            <td>30</td>
            <td>110</td>
            <td>30</td>
            <td>18</td>
            <td>1000</td>
            <td>1000</td>
            <td>500</td>
            <td>1000</td>
            <td>1000</td>
            <td style="padding: 0px; text-align: center">PURE WT
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>0.00</td>
                        <td>0.9</td>
                    </tr>
                </table>
            </td>
            <td style="padding: 0px; text-align: center">TOTAL PKR
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>0.00</td>
                        <td>4000</td>
                    </tr>
                </table>
            </td>
            <td style="padding: 0px; text-align: center">CURRENCY
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>0.00</td>
                        <td>500</td>
                    </tr>
                </table>
            </td>
            <td>0.9 Cr</td>
            <td>4000 Cr</td>
            <td>500 Cr</td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="25"><h5><u>Payments:</u></h5></td>
        </tr>
        <tr>
            <td>V.NO</td>
            <td>Date</td>
            <td>DESCRIPTIONS</td>
            <td colspan="15"></td>
            <th style="padding: 0px; text-align: center">PURE WT
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>Dr</td>
                        <td>Cr</td>
                    </tr>
                </table>
            </th>
            <th style="padding: 0px; text-align: center">TOTAL PKR
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>Dr</td>
                        <td>Cr</td>
                    </tr>
                </table>
            </th>
            <th style="padding: 0px; text-align: center">CURRENCY
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>Dr</td>
                        <td>Cr</td>
                    </tr>
                </table>
            </th>
            <th>GOLD BAL</th>
            <th>PKR BAL</th>
            <th>CURRENCY BAL</th>
        </tr>
        <?php for($i=0; $i<5; $i++){ ?>
        <tr>
            <td>001</td>
            <td>14-11-2020</td>
            <td>a afasfasfaf</td>
            <td colspan="15"></td>
            <th style="padding: 0px; text-align: center">PURE WT
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>0.9</td>
                        <td>0.00</td>
                    </tr>
                </table>
            </th>
            <th style="padding: 0px; text-align: center">TOTAL PKR
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>2000</td>
                        <td>0.00</td>
                    </tr>
                </table>
            </th>
            <th style="padding: 0px; text-align: center">CURRENCY
                <table class="table-bordered" style="width: 100; text-align: center">
                    <tr>
                        <td>200</td>
                        <td>0.00</td>
                    </tr>
                </table>
            </th>
            <th>0.9 Dr</th>
            <th>2000 Dr</th>
            <th>200 Dr</th>
        </tr>
        <?php } ?>
    </table>

</div>