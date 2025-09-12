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
                        <h3 class="text-22 text-midnight text-bold mb-4">NEW SALES INCENTIVE SLIP
                        </h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('hr.new_incentive_index') !!}" id="back_btn"
                                   class="btn btn-primary btn-sm ">
                                    Back </a>
                            </div>
                        </div>

                        <div class="box-body" style="margin-top:50px;">
                            <div class="row mt-2">
                                <div class="col-lg-6">
                                    <label for="slab_name"> <b>Agent</b> <b>*</b> </label>
                                    @isset($new_sales->agent)   <input readonly class=" form-control"
                                                                       style="color:black"
                                                                       value="{{$new_sales->agent->name}}"/>
                                    @endisset
                                </div>
                                <div class="col-lg-6">
                                    <label for="min"><b> Student Count</b></label>
                                    @isset($new_sales->count)    <input readonly type="text" class="form-control"
                                                                        style="color:black"
                                                                        value="{{$new_sales->count}}"
                                    />@endisset
                                </div>
                            </div>

                            <div class="row mt-2">


                                <div class="col-lg-6">
                                    <label for="max"><b> Student Fees </b></label>
                                    @isset($new_sales->student_fee) <input readonly type="text"
                                                                           class="form-control" style="color:black"
                                                                           value="{{$new_sales->student_fee}}"
                                    /> @endisset
                                </div>
                                <div class="col-lg-6">
                                    <label for="comission"><b>Percentage Incentive</b> </label>
                                    @isset($new_sales->percentage)   <input readonly value="{{$new_sales->percentage}}"
                                                                            class="form-control"
                                                                            style="color:black">@endisset

                                </div>


                            </div>

                            <div class="row mt-2">

                                <div class="col-lg-6">
                                    <label for="slab_type"><b> Commission </b></label>
                                    @isset($new_sales->commission)    <input readonly type="text"
                                                                             class="form-control" style="color:black"
                                                                             value="{{$new_sales->commission}}"> @endisset
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

