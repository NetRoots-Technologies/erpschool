@extends('admin.layouts.main')

@section('title')
    Student Fee  Create
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Paid Fee</h3>
                        <div class="row    mt-4 mb-4 ">
                            <div class="col-12 text-right">
                                <a href="{!! route('admin.paid_student_fee.index') !!}" class="btn btn-primary btn-sm ">
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
                        <div class="w-100">
                            <form action="{!! route('admin.paid_student_fee.store') !!}" enctype="multipart/form-data"
                                  autocomplete="off" method="post">
                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <h5>Student Fee</h5>
                                    <div class="row mt-2">
                                        <div class="col-lg-6">
                                            <label for="data_bank_id">Select Student*</label>
                                            <select name="data_bank_id" class="select2 form-control"
                                                    id="data_bank_id">
                                                <option value="" disabled selected>Select Databank Student</option>
                                                @foreach($data['databank'] as $item)
                                                    <option value="{!! $item->id !!}">{!! $item->name !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="course_id">Installement No</label>
                                            <select required name="course_id[]" id="course_id"
                                                    class="select2 form-control"
                                                    >

                                                @foreach ($data['course'] as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class=" row mt-5 mb-3">
                                        <div class="col-12">
                                            <div class="form-group text-right">
                                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                                <a href="{!! route('admin.paid_student_fee.index') !!}"
                                                   class=" btn btn-sm btn-danger">Cancel </a>
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

        $("#course_id").on("select2:select select2:unselect", function (e) {

            var items = $(this).val();


            $.ajax({
                method: 'GET',
                url: "{{ route('admin.fee') }}",
                data: {
                    "id": items,
                },
                success: function (response) {

                    $('#course_fee').val(response);

                }
            });


        })


    </script>


@endsection

