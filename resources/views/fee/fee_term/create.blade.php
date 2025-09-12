@extends('admin.layouts.main')

@section('title')
    Fee Term
@stop
<style>
    .input-md{
        width: 20px!important;
    }
</style>

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                            <h3 class="text-22 text-midnight text-bold mb-4"> Create Fee Term</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.fee-terms.index') !!}" class="btn btn-primary btn-md">
                                    Back </a>
                            </div>
                        </div>

                        <form action="{!! route('admin.fee-terms.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            @csrf


                            <div class="w-100 p-3">
                                <div class="box-body" style="margin-top:10px;">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <label for="Academic"><b>Academic Session *</b></label>
                                            <select name="session_id"
                                                    class="form-control session_select  select2 basic-single"
                                                    required id="session_id">
                                                <option>Select Session</option>
                                                @foreach($sessions as $key => $item)
                                                    <option value="{!! $key !!}">{!! $item !!}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="branches"><b>Company *</b></label>
                                            <select name="company_id"
                                                    class="form-control  select2 basic-single company_select"
                                                    required id="companySelect">
                                                <option selected>Select Company</option>
                                                @foreach($companies as $item)
                                                    <option
                                                        value="{{$item->id}}">{{ $item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="input-label">
                                                    <label class="branch_Style"><b>Branch*</b></label>
                                                </div>
                                                <select name="branch_id"
                                                        class="form-control  select2 basic-single branch_select"
                                                        required id="branch_id">
                                                    <option selected>Select Branch</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <label for="classes"><b>Class: *</b></label>
                                            <select required name="class_id"
                                                    class="form-select select2 basic-single mt-3 class_select"
                                                    aria-label=".form-select-lg example">

                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="terms"><b>Term: *</b></label>
                                            <select required name="term"
                                                    class="form-select select2 basic-single mt-3 select_term"
                                                    aria-label=".form-select-lg example">
                                                <option>Select Term</option>
                                                @foreach($terms as $key => $item)
                                                    <option value="{!! $key !!}">{!! $item !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="panel-body pad table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr style="text-align:center; background-color:#025CD8;">
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Voucher</th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Voucher Due Date</th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Starting Date</th>
                                        <th style="text-align:center;background-color:#025CD8;color: white;">Ending Date</th>
                                    </tr>
                                    </thead>
                                    <tbody id="loadData"></tbody>
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
                            branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });
            }).change();


            $('.branch_select').on('change', function () {

                var branch_id = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('academic.fetchClass') }}',
                    data: {
                        branch_id: branch_id
                    },
                    success: function (data) {
                        var classDropdown = $('.class_select').empty();
                        classDropdown.append('<option value="">Select class</option>');

                        data.forEach(function (academic_class) {
                            classDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching branches:', error);
                    }
                });

            });
        })
    </script>




    <script>

        $(document).ready(function () {
            $('.select_term').on('change', function () {
                var x = $(this).val();
                var html = "";
                for (var count = 0; count < x; count++) {
                    var id = count + 1;
                    html += '<tr><td><strong>' + id + '<sup>st</sup> <span class="term_display_title">Voucher Date</span></strong></td><td><input  placeholder="YYYY-MM-DD" class="input-lg form-control my-custom-datepicker-field" data-date-format="yyyy-mm-dd" required="" id="group1" name="voucher_date[]" type="date" ></td><td><input  placeholder="YYYY-MM-DD" class="input-lg form-control my-custom-datepicker-field" data-date-format="yyyy-mm-dd" required="" name="starting_date[]" type="date" ></td><td><input  placeholder="YYYY-MM-DD" class="input-lg form-control my-custom-datepicker-field" data-date-format="yyyy-mm-dd" required="" name="ending_date[]" type="date"></td></tr>';
                }
                $('#loadData').html(html);
            });
        });
    </script>



@endsection
