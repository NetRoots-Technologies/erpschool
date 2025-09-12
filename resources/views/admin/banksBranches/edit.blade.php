@extends('admin.layouts.main')

@inject('helper', 'App\Helper\helper')

@section('content')

    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Update Bank Branch</strong> <span class="float-end"><a
                                    href="{{url('/admin/banksBranches')}}" class="btn btn-primary" type="submit"
                                    value="Save">Back</a></span></div>
                        <div class="card-body">
                            <form method="POST" action="{{route('admin.banksBranches.update',$data->id)}}">
                                {{--                            <form action="{{route('admin.permissions.store')}}" method="post" enctype="multipart/form-data">--}}
                                @csrf
                                {{ method_field('PUT') }}
                                <div class="row">


                                    <input type="hidden" name="id" value="{{$data->id}}">


                                    <div class="col-md-6">
                                        <label>Bank Name</label>
                                        <select class="form-control select2" name="bank_id" id="exampleFormControlSelect1">
                                            @foreach($helper::getBanks() as $bank)
                                                <option @if($bank->bank_id) selected @endif value="{{$bank['bank_id']}}"   >{{$bank['bank_name']}}</option>
                                            @endforeach
                                        </select>
{{--                                        <select name="bank_id" class="select2 form-control" required>--}}
{{--                                            <option value="" disabled selected>Select Bank Name</option>--}}
{{--                                            @if(count($bank))--}}
{{--                                            @foreach($bank as $key => $value)--}}
{{--                                                <option @if($bank->bank_id == $value) selected @endif value="{{$value}}">{{$key}}</option>--}}
{{--                                            @endforeach--}}
{{--                                            @endif--}}
{{--                                        </select>--}}
                                    </div>

                                    <div class=" col-md-4">
                                        <label for="name">Branch Code</label>
                                        <input type="text" name="branch_code_id" class="form-control"
                                               value="{{$data->branch_code_id}}"
                                               autocomplete="off">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="name">Address</label>
                                        <input type="text" name="address" class="form-control"
                                               value="{{$data->address}}"
                                               autocomplete="off">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="name">Number</label>
                                        <input type="text" name="number" class="form-control" value="{{$data->number}}"
                                               autocomplete="off">
                                    </div>


                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Update Branch</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
