@extends('admin.layouts.main')
@section('title', 'General Ledger')
@section('content')


<div class="container-fluid">
    <div class="card p-4">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" id="general-ledger">
                    @CSRF
                    <label>Detail Type</label>
                    <div class="row gy-3">
                        <div class="col-md-6">
                            {{-- {{print_r($coa)}} --}}
                            {{-- {{ dd($coa->toArray()) }} --}}
                            <select name="coa" class="form-select select2" id="coa">
                                @foreach ($vendor as $item)
                                    <option value="{{$item->code}}">{{ $item->code.' - '. $item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">View Ledger</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card p-4 ledger-result">
        <div class="row">
            <div class="col-md-12">

                <div class="mb-3">
                    <h5 >ACCOUNT TITLE : <span id="account-title">[100-01-01-0002] EQUIPMENT</span></h5>
                    <p>[Period From 01-Jul-2025 To 30-Jul-2025]</p>
                </div>
                <!-- Responsive Summary Table -->
                <div class="table-responsive">
                    <table class="table table-bordered border-bottom text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ATT.</th>
                                <th>VOUCHER DATE</th>
                                <th>VOUCHER NO.</th>
                                <th>SITE</th>
                                <th>NARRATION</th>
                                <th>CHEQUE NO.</th>
                                <th>CLEARANCE DATE</th>
                                <th>DEBIT</th>
                                <th>CREDIT</th>
                                <th>BALANCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="fw-bold">BALANCE BROUGHT FORWARD</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td id="balance">92,333</td>
                            </tr>
                            <tr>
                                <td>📎</td>
                                <td>15-Jul-2025</td>
                                <td><a href="#">CPV-4</a></td>
                                <td>TT</td>
                                <td>TRANSPORT</td>
                                <td></td>
                                <td></td>
                                <td>20,000</td>
                                <td></td>
                                <td>112,333</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-end">Ledger Total</th>
                                <td>20,000</td>
                                <td>0</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-end">Less: Reserved</th>
                                <td>0</td>
                                <td>0</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-end">Net Total</th>
                                <td>20,000</td>
                                <td>0</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .ledger-result
        {
            display: none;
        }
    </style>
@endsection


@section('js')

    <script>
        $(document).ready(function () {
            $('#coa').select2(); 
            $("#general-ledger").on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: '{{ route("admin.accounts.general-ledger") }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val() // Laravel CSRF token
                    },
                    success: function (response) {
                        $("#account-title").text(`[${response[0].code}] ${response[0].name}`);
                        $("#balance").text(`${response[0].balance}`);
                        $('.ledger-result').slideDown();
                        // console.log("Success:", response[0].name);
                        // alert(response);
                    },
                    error: function (xhr) {
                        console.error("Error:", xhr.responseText);
                        alert("An error occurred. Check console.");
                    }
                });

            })
        })
    </script>

@endsection