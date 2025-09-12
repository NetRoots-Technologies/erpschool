@extends('admin.layouts.main')

@section('title')
    Slip
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4">NEW RECOVERY INCENTIVE SLIP
                        </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.new_sale_recovery_index') !!}" id="back_btn"
                                   class="btn btn-primary btn-sm ">
                                    Back </a>
                            </div>
                        </div>

                        <div class="box-body" style="margin-top:50px;">
                            <div class="row mt-2">
                                <div class="col-lg-6">
                                    <label for="slab_name"> <b>Start Date</b>  </label>
                                    @isset($new_recovery->start_date)   <input readonly class=" form-control"
                                                                       style="color:black"
                                                                       value="{{$new_recovery->start_date}}"/>
                                    @endisset
                                </div>
                                <div class="col-lg-6">
                                    <label for="min"><b>End Date</b></label>
                                    @isset($new_recovery->end_date)    <input readonly type="text" class="form-control"
                                                                        style="color:black"
                                                                        value="{{$new_recovery->end_date}}"
                                    />@endisset
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-lg-6">
                                    <label for="slab_name"> <b>Agent</b>   </label>
                                    @isset($new_recovery->agent)   <input readonly class=" form-control"
                                                                       style="color:black"
                                                                       value="{{$new_recovery->agent->name}}"/>
                                    @endisset
                                </div>
                                <div class="col-lg-6">
                                    <label for="min"><b> Recovered Percentage</b></label>
                                    @isset($new_recovery->recovered_percentage)    <input readonly type="text" class="form-control"
                                                                        style="color:black"
                                                                        value="{{$new_recovery->recovered_percentage}}"
                                    />@endisset
                                </div>
                            </div>

                            <div class="row mt-2">


                                <div class="col-lg-6">
                                    <label for="max"><b> Total Paid Installments </b></label>
                                    @isset($new_recovery->total_paid_installment) <input readonly type="text"
                                                                           class="form-control" style="color:black"
                                                                           value="{{$new_recovery->total_paid_installment}}"
                                    /> @endisset
                                </div>
                                <div class="col-lg-6">
                                    <label for="comission"><b>Total Student Fee</b> </label>
                                    @isset($new_recovery->total_student_fee)   <input readonly value="{{$new_recovery->total_student_fee}}"
                                                                            class="form-control"
                                                                            style="color:black">@endisset

                                </div>


                            </div>

                            <div class="row mt-2">

                                <div class="col-lg-6">
                                    <label for="slab_type"><b> Incentive Percentage </b></label>
                                    @isset($new_recovery->incentive_percentage)    <input readonly type="text"
                                                                             class="form-control" style="color:black"
                                                                             value="{{$new_recovery->incentive_percentage}}"> @endisset
                                </div>
                                <div class="col-lg-6">
                                    <label for="slab_type"><b> Commission </b></label>
                                    @isset($new_recovery->commission)    <input readonly type="text"
                                                                             class="form-control" style="color:black"
                                                                             value="{{$new_recovery->commission}}"> @endisset
                                </div>
                            </div>
                        </div>

                        <hr style="background-color: darkgray">
                        <div class="row mt-8 mb-3">
                            <div class="col-12">
                                <div class="form-group text-right">

                                    <button class="btn btn-sm btn-primary" id="print-button">Print</button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
@stop
@section('css')
    <style>
        @media print {
            /* Hide the "Print" button in the printed view */
            #print-button {
                display: none;
            }

            #back_btn {
                display: none;
            }


        }
    </style>
@endsection
@section('js')
    <script>
        const printButton = document.getElementById('print-button');
        printButton.addEventListener('click', () => {
            window.print();
        });
    </script>
@stop

