@inject('request', 'Illuminate\Http\Request')
@inject('CoreAccounts', 'App\Helpers\CoreAccounts')
@inject('Ledgers', 'App\Models\Accounts\AccountLedger')
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
<div class="panel-body pad table-responsive">
    <div class="col-md-6">
        <h4>Ledger Statement for {{ $Ledger->name }} from {{ $start_date }} to {{ $end_date }}</h4>
    </div>
    @if($request->get('medium_type') == 'web')
        <div class="col-md-6">
            <div class="text-center pull-right">
                <button onclick="FormControls.printReport('excel');" type="button" class="btn bg-olive btn-flat"><i class="fa fa-file-excel-o"></i>&nbsp;Excel</button>
                <button onclick="FormControls.printReport('pdf');" type="button" class="btn btn-danger btn-flat"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</button>
                <button onclick="FormControls.printReport('print');" type="button" class="btn btn-flat"><i class="fa fa-print"></i>&nbsp;Print</button>
            </div>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Date</th>
            <th>Number</th>
            <th>Entry Type</th>
            <th>Description</th>
            <th style="text-align: right;">Debit ({{ $DefaultCurrency->code }})</th>
            <th style="text-align: right;">Credit ({{ $DefaultCurrency->code }})</th>
            <th style="text-align: right;">Balance ({{ $DefaultCurrency->code }})</th>
        </tr>
        </thead>
        <tbody>
            @php
                $entry_balance['amount'] = $op['amount'];
                $entry_balance['dc'] = $op['dc'];
            @endphp
            <tr class="bg-filled">
                <td colspan="6">Current opening balance</td>
                <td align="right">{{ $CoreAccounts::toCurrency($entry_balance['dc'], $entry_balance['amount']) }}</td>
            </tr>
            @foreach ($Entries as $entry)
                @php
                    /* Calculate current entry balance */
                    $entry_balance = $CoreAccounts::calculate_withdc(
                        $entry_balance['amount'], $entry_balance['dc'],
                        $entry['amount'], $entry['dc']
                    );
                @endphp
                @php($i = 0)
                <tr class="tr-highlight">
                    <td>{{ $entry->voucher_date }}</td>
                    <td>{{ $entry->number }}</td>
                    {{--<td>{{ $Ledgers->entryLedgers($entry->id) }}</td>--}}
                    <td>{{ $EntryTypes[$entry->entry_type_id]->code }}</td>
                    <td>{{ $entry->narration }}</td>
                    @if ($entry['dc'] == 'd')
                        <td align="right">{{ $CoreAccounts::toCurrency('d', $entry['amount']) }}</td>
                        <td>&nbsp;</td>
                    @elseif ($entry['dc'] == 'c')
                        <td>&nbsp;</td>
                        <td align="right">{{ $CoreAccounts::toCurrency('c', $entry['amount']) }}</td>

                    @else
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    @endif
                    <td align="right">{{ $CoreAccounts::toCurrency($entry_balance['dc'], $entry_balance['amount']) }}</td>

                </tr>
            @endforeach
                <!-- Current closing balance -->
                <tr class="bg-filled">
                    <td colspan="6">Current closing balance</td>
                    <td align="right">{{ $CoreAccounts::toCurrency($entry_balance['dc'], $entry_balance['amount']) }}</td>
                </tr>

        </tbody>
    </table>
</div>