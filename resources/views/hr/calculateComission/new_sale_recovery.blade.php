@extends('admin.layouts.main')

@section('title')
    SALES RECOVERY
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4">SALES RECOVERY
                        </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.new_sale_recovery_index') !!}" class="btn btn-primary btn-sm ">
                                    Back </a>
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
                        <form action="{!! route('hr.recovery_incentive_post') !!}" enctype="multipart/form-data"
                              autocomplete="off" method="post">
                            <div class="w-100">
                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <div class="row mt-4 mb-4">

                                        <div class="col-md-6">
                                            <label for="name"> Start Date <b>*</b> </label>
                                            <input type="text" class="form-control" name="start_date" id="datepicker"
                                                   autocomplete="off"/>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="name"> End Date <b>*</b> </label>
                                            <input type="text" class="form-control" name="end_date" id="datepicker1"
                                                   autocomplete="off"/>
                                        </div>

                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-12">
                                            <label for="slab_name"> <b>Agent</b> <b>*</b> </label>
                                            <select class="select2 form-control" name="agent" id="agent_id">
                                                <option>Select Agent</option>
                                                @foreach($agents as $agent)
                                                    <option value="{{$agent->id}}">{{$agent->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-lg-12 mt-1">
                                            <label for="agent_type_id"><b> Student </b></label>
                                            {{--                                            <select--}}
                                            {{--                                                    class="select2 form-control" >--}}

                                            {{--                                            </select>--}}
                                            <table>

                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                </tr>
                                                </thead>
                                                <tbody id="student">

                                                </tbody>
                                                {{--                                                <tr>--}}
                                                {{--                                                    <th>Name</th>--}}
                                                {{--                                                    <th>Session</th>--}}
                                                {{--                                                </tr>--}}
                                                {{--                                                <tr>--}}
                                                {{--                                                    <td></td>--}}
                                                {{--                                                    <td></td>--}}
                                                {{--                                                </tr>--}}

                                            </table>

                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="min"><b> Recovered Percentage</b></label>
                                            <input readonly name="recovered_percentage" id="recovered_percentage"
                                                   type="text" class="form-control"
                                                   style="color:black"
                                            />
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="max"><b> Total Paid Installments </b></label>
                                            <input readonly name="total_paid_installment" id="total_paid_installment"
                                                   type="text"
                                                   class="form-control" style="color:black"
                                            />
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="comission"><b>Total Student Fee</b> </label>
                                            <input readonly="" id="total_student_fee"
                                                   class="form-control" name="total_student_fee" style="color:black">

                                        </div>
                                        <div class="col-lg-6">
                                            <label for="comission"><b>Percentage Incentive</b> </label>
                                            <input readonly="" id="percentage"
                                                   class="form-control" name="incentive_percentage" style="color:black">

                                        </div>

                                        <input hidden  id="paid_fee_id" name="paid_fee_id">
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="slab_type"><b> Commission </b></label>
                                            <input readonly name="commission" id="commission" type="text"
                                                   class="form-control" style="color:black">
                                        </div>
                                    </div>
                                </div>

                                <hr style="background-color: darkgray">
                                <div class="row mt-8 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">

                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('css')
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

@endsection
@section('js')

    <script src="{{asset('dist/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script
        src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js')}}"></script>

    <script>
        $("#datepicker").datepicker({
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });
        $("#datepicker1").datepicker({
            viewMode: "date",
            multidate: false,
            multidateSeparator: "-",
        });
        $('#agent_id').on('change', function () {
            var agent_id = this.value;
            var start_date = $('#datepicker').val();
            var end_date = $('#datepicker1').val();

            $("#student").html('');
            $.ajax({
                url: "{{route('hr.fetch_agent_student_recovery')}}",
                method: "POST",
                async: false,
                data: {
                    agent_id: agent_id,
                    start_date: start_date,
                    end_date: end_date,
                    _token: '{{csrf_token()}}'
                },
                success: function (data) {

                    if (data.paid_intallment_fee > 0){
                        $('#student').html(data.html);
                        $('#recovered_percentage').val(data.recovered_percentage.toFixed(2));
                        $('#total_paid_installment').val(data.paid_intallment_fee);
                        $('#total_student_fee').val(data.student_fee);
                        $('#paid_fee_id').val(data.paid_ids);
                    }
                    else {
                        alert('Commission Already Calculated')
                    }

                }
            });
        });


        $(document).ready(function () {

            $('#agent_id').on('change', function () {

                var studentCount = parseInt($('#recovered_percentage').val());

                if (studentCount >= 0 && studentCount <= 49) {
                    $('#percentage').val('0');
                } else if (studentCount >= 50 && studentCount <= 65) {
                    $('#percentage').val('2');
                } else if (studentCount >= 66 && studentCount <= 74) {
                    $('#percentage').val('3');
                } else if (studentCount >= 75 && studentCount <= 89) {
                    $('#percentage').val('5');
                } else if (studentCount >= 90 && studentCount <= 99) {
                    $('#percentage').val('7');
                } else if (studentCount == 100) {
                    $('#percentage').val('10');
                } else {
                    $('#percentage').val('');
                }
            });

        });

        function calculateTotal() {
            var total_paid_installment = $('#total_paid_installment').val();
            var taxPercentage = parseFloat($('#percentage').val());
            var taxAmount = total_paid_installment * (taxPercentage / 100);
            $('#commission').val(taxAmount.toFixed(2));
        }

        $(document).ready(function () {
            $('#agent_id').on('change', function () {
                calculateTotal();
            });
        });


    </script>
@endsection

