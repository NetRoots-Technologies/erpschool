@extends('admin.layouts.main')

@section('title')
Student Fee
@stop

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card basic-form">
                <div class="card-body">
                    <h3 class="text-22 text-midnight text-bold mb-4"> Create Student Fee</h3>
                    <div class="row    mt-4 mb-4 ">
                        <div class="col-12 text-right">
                            <a href="{{ route('admin.student-regular-fee.index') }}" class="btn btn-primary btn-md">
                                Back </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.student-regular-fee.store') }}" enctype="multipart/form-data"
                        id="form_validation" autocomplete="off" method="post">
                        @csrf

                        <div class="w-100 p-3">
                            <div class="box-body" style="margin-top:10px;">
                                <div class="row">

                                    <div class="col-md-4">
                                        <label for="Academic"><b>Academic Session *</b></label>
                                        <select name="session_id"
                                            class="form-control session_select  select2 basic-single" required
                                            id="session_id">
                                            <option value="" selected disabled>Select Session</option>
                                            @foreach($sessions as $key => $item)
                                            <option value="{!! $key !!}">{!! $item !!}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="branches"><b>Company *</b></label>
                                        <select name="company_id"
                                            class="form-control  select2 basic-single company_select" required
                                            id="companySelect">
                                            <option value="" selected disabled>Select Company</option>
                                            @foreach($companies as $item)
                                            <option value="{{$item->id}}">{{ $item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label class="branch_Style"><b>Branch*</b></label>
                                            </div>
                                            <select name="branch_id"
                                                class="form-control  select2 basic-single branch_select" required
                                                id="branch_id">
                                                <option value="" selected disabled>Select Branch</option>

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
                                        <label for="month"><b>Select a month:*</b></label>
                                        <input type="month" class="form-control" value="{{ now()->format('Y-m') }}"
                                            id="month" name="generated_month" required>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="panel-body pad table-responsive">
                            {{-- <table class="table table-bordered">--}}
                                {{-- <thead>--}}
                                    {{-- <tr style="text-align:center; background-color:#025CD8;">--}}
                                        {{-- <th style="text-align:center;background-color:#025CD8;color: white;">--}}
                                            {{-- Sr.No</th>--}}
                                        {{-- <th style="text-align:center;background-color:#025CD8;color: white;">--}}
                                            {{-- <input type="checkbox" id="checkAll">--}}
                                            {{-- </th>--}}
                                        {{-- <th style="text-align:center;background-color:#025CD8;color: white;">
                                            Students</th>--}}
                                        {{-- <th style="text-align:center;background-color:#025CD8;color: white;">Amount
                                        </th>--}}
                                        {{-- <th style="text-align:center;background-color:#025CD8;color: white;">
                                            Discount(%)</th>--}}
                                        {{-- <th style="text-align:center;background-color:#025CD8;color: white;">
                                            Discount(RS)</th>--}}
                                        {{-- <th style="text-align:center;background-color:#025CD8;color: white;">claim
                                            1</th>--}}
                                        {{-- <th style="text-align:center;background-color:#025CD8;color: white;">claim
                                            2</th>--}}
                                        {{-- <th style="text-align:center;background-color:#025CD8;color: white;">Total
                                            Amount</th>--}}
                                        {{-- </tr>--}}
                                    {{-- </thead>--}}
                                {{-- <tbody id="loadData"></tbody>--}}
                                {{-- </table>--}}
                            <div id="loadData"></div>
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
            function fetch_fee() {
                var formData = $('#form_validation').serialize();
                // formData += '&page=' + page;
                var loader = $('<div class="loader"></div>').appendTo('body');

                $.ajax({
                    url: "{{ route('admin.studentRegularFee.data') }}",
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        loader.remove();
                        $('#loadData').html(data);
                    },
                    error: function (request, error) {
                        loader.remove();
                        console.log("Request: " + JSON.stringify(request));
                    }
                });
            }

            $('.class_select').on('change', function () {
                fetch_fee();
            });

            // $(document).on('click', '.pagination a', function (event) {
            //     event.preventDefault();
            //     var page = $(this).attr('href').split('page=')[1];
            //     fetch_fee(page);
            // });
        });

</script>


@endsection
