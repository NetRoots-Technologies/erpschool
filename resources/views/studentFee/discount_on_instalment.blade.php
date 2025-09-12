@extends('admin.layouts.main')

@section('title')
    Student Discount
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">


                        <h3 class="text-22 text-midnight text-bold mb-4"> Discount</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.fee_paid_detail',$data['student_fee']->id) !!}"
                                   class="btn btn-primary btn-sm ">
                                    Back </a>
                            </div>
                        </div>
                        {{--                        @dd($roles)--}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="w-100">
                            <form action="{!! route('admin.discount_on_instalment_post',$data['student_fee']->id) !!}"
                                  enctype="multipart/form-data"
                                  autocomplete="off" method="post">
                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <h5>Student Fee</h5>


                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="student_fee">Student Remaning Pay Amount*</label>
                                            <input name="Remaning" type="number" min="0" class="form-control"
                                                   required="required" readonly
                                                   id="Remaning" value="{!! $data['student_fee']->remaining_amount !!}"
                                                   required/>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="student_fee">Give Discount</label>
                                            <input name="discount" type="number" min="0" class="form-control"
                                                   id="discount_amount" value="" required/>
                                        </div>
                                    </div>


                                    <div class=" row mt-5 mb-3">
                                        <div class="col-12">
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn btn-sm btn-primary">Give Discount
                                                </button>
                                                <a href="{!! route('admin.fee_paid_detail',$data['student_fee']->id) !!}"
                                                   class=" btn btn-sm btn-danger">Cancel </a>
                                            </div>
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
    </div>
@stop
@section('css')


@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $("#discount_amount").change(function () {
                var discount_amount = $("#discount_amount").val();
                var Remaning = $("#Remaning").val();
                var discounted_amount = Number(Remaning) - Number(discount_amount);
                $('#Remaning').val(discounted_amount);
            });
        });
    </script>
@endsection

