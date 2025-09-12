@extends('admin.layouts.main')

@section('title')
    Student Fee
@stop

@section('content')

    @php
        $totalMonthlyAmount = 0;
        $totalDiscount = 0;
        $totalDiscountRupees = 0;
        $totalClaim1 = 0;
        $totalClaim2 = 0;
    @endphp

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Update Student Fee</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.student-regular-fee.index') !!}"
                                   class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('admin.student-regular-fee.update',$studentFee->id) !!}"
                              enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf
                            @method('put')

                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:10px;">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label for="Academic"><b>Academic Session </b></label>
                                            <select name="session_id"
                                                    class="form-control session_select  select2 basic-single select_option"
                                                    readonly="" id="session_id">
                                                @foreach($sessions as $key => $item)
                                                    <option
                                                        value="{!! $key !!}" {!! $studentFee->session_id == $key ? 'selected' : '' !!}>{!! $item !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Company </b></label>
                                            <select name="company_id"
                                                    class="form-control  select2 basic-single company_select select_option"
                                                    readonly id="companySelect" required>
                                                @foreach($companies as $item)
                                                    <option
                                                        value="{{$item->id}}" {!! $studentFee->company_id == $item->id ? 'selected' : '' !!}>{{ $item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="branch_Style"><b>Branch</b></label>
                                                </div>
                                                <select name="branch_id"
                                                        class="form-control  select2 basic-single branch_select select_option"
                                                        readonly id="branch_id" required>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label for="classes"><b>Class: </b></label>
                                            <select required name="class_id"
                                                    class="form-select select2 basic-single mt-3 class_select select_option"
                                                    readonly aria-label=".form-select-lg example">

                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="month"><b>Select a month:*</b></label>
                                            <input type="month" class="form-control" readonly value="{{ $studentFee->generated_month }}" id="month" name="generated_month">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>


                            <div class="card-body table-responsive mb-5">
                                <div>
                                    <b>Student: {{ $studentFee->student->first_name }} {{ $studentFee->student->last_name }}</b>
                                    <input type="hidden" name="student_id" value="{{$studentFee->student->id ?? ''}}">
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr style="text-align:center; background-color:#025CD8;">
                                        <th style="text-align:center;background-color:#025CD8;color: white;">
                                            Sr.No
                                        </th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Fee Head
                                        </th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Amount</th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Discount
                                        </th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Ds 2</th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">claim 1
                                        </th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">claim 2
                                        </th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Total
                                            Amount
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="loadData">
                                    @if(isset($studentFee->student_fee_data))
                                        @foreach($studentFee->student_fee_data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{--                                                    $studentFee->student_fee_data->feeHead->fee_head--}}
                                                    {{ @$item->feeHead->fee_head }}
                                                    <input type="hidden" name="fee_head_id[]"
                                                           value="{!! $item->feeHead->id ?? '' !!}">
                                                </td>
                                                {{--                                        <input type="hidden" name="fee_structure_id[]" value="{{ $item->id ?? '' }}">--}}
                                                <td><input type="number" name="monthly_amount[]"
                                                           class="form-control total-monthly-amount"
                                                           value="{{ $item->monthly_amount ?? '' }}"></td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" name="discount[]"
                                                               value="{{ $item->discount_percent ?? 0 }}"
                                                               class="form-control discount-percentage change-amount">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" name="discount_rupees[]"
                                                               value="{{ $item->discount_rupees ?? 0 }}"
                                                               class="form-control discount-rupees change-amount">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" name="claim_1[]"
                                                               value="{{ $item->claim1 ?? 0 }}"
                                                               class="form-control claim-1 change-amount">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" name="claim_2[]"
                                                               value="{{ $item->claim2 ?? 0 }}"
                                                               class="form-control claim-2 change-amount">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" name="total_amount_after_discount[]"
                                                               value="{{ $item->total_amount_after_discount ?? 0 }}"
                                                               class="form-control total-amount-after-discount"
                                                               readonly>
                                                    </div>
                                                </td>
                                                @php
                                                    $totalMonthlyAmount += $feeHead->feeStructureVal->total_amount_after_discount ?? 0;
                                                    $totalDiscount += $discountPercent ?? 0;
                                                    $totalDiscountRupees += $feeHead->feeStructureVal->discount_rupees ?? 0;
                                                    $totalClaim1 += $feeHead->feeStructureVal->claim1 ?? 0;
                                                    $totalClaim2 += $feeHead->feeStructureVal->claim2  ?? 0;
                                                @endphp
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2">Total Amount</td>
                                            <td id="totalMonthlyAmount">{{ $totalMonthlyAmount }}</td>
                                            <td id="totalDiscount">{{$totalDiscount}}</td>
                                            <td id="totalDiscountRupees">{{ $totalDiscountRupees }}</td>
                                            <td id="claim1total">{{ $totalClaim1 }}</td>
                                            <td id="claim2total">{{ $totalClaim1 }}</td>
                                            <td id="total_amount_after_discount">{{ $totalMonthlyAmount }}</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"
                                        style="margin-bottom: 10px;margin-left: 10px;">Save
                                </button>
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
        var branch_id;
        $(document).ready(function () {


            $('#companySelect').on('change', function () {
                var selectedCompanyId = $('#companySelect').val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('hr.fetch.branches') }}',
                    data: {
                        companyid: selectedCompanyId
                    },
                    success: function (data) {
                        var branchesDropdown = $('.branch_select').empty();

                        branchesDropdown.append('<option value="">Select Branch</option>');

                        data.forEach(function (branch) {

                            var selectedBranch = branch.id == '{{ $studentFee->branch_id }}' ? 'selected' : '';

                            branchesDropdown.append('<option value="' + branch.id + '" ' + selectedBranch + '>' + branch.name + '</option>');

                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


            $('.branch_select').on('change', function () {
                branch_id = $(this).val();
                console.log(branch_id);
                if (branch_id == null) {
                    branch_id = {!! $studentFee->branch_id !!};
                }
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var sectionDropdown = $('.class_select').empty();

                        data.forEach(function (academic_class) {
                            var selectedClass = academic_class.id == '{{ $studentFee->class_id }}' ? 'selected' : '';

                            sectionDropdown.append('<option value="' + academic_class.id + '" ' + selectedClass + '>' + academic_class.name + '</option>');

                        });

                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            }).change();
        })

        $('.select_option').prop('disabled',true);

    </script>



        @include('fee.fee_js.js')




@endsection
