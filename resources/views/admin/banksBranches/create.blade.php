@extends('admin.layouts.main')

@inject('helper', 'App\Helper\helper')
@section('content')
    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Add Branch</strong> <span class="float-end"><a
                                    href="{{url('/admin/banksBranches')}}" class="btn btn-primary" type="submit"
                                    value="Save">Back</a></span>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('admin.banksBranches.store')}}">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6">
                                        <label>Bank Name</label>
                                        <select class="form-control select2" name="bank_id" id="exampleFormControlSelect1">
                                            <option value="0">Select Bank Name</option>
                                            @foreach($helper::getBanks() as $bank)
                                                <option value="{{$bank['id']}}">{{$bank['bank_name']}}</option>
                                            @endforeach
                                        </select>
{{--                                        <select name="bank_id" class="select2 form-control" required>--}}
{{--                                            <option value="" disabled selected>Select Bank</option>--}}
{{--                                            @foreach($bank as $key => $value)--}}
{{--                                                <option value="{{$value}}">{{$key}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
                                    </div>

                                    <div class="col-md-6">
                                        <label for="name">Branch Code</label>
                                        <input required type="text" name="branch_code_id" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name">Address</label>
                                        <input required type="text" name="address" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name">Number</label>
                                        <input required type="text" name="number" class="form-control" autocomplete="off">
                                    </div>

                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Add Branch</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
