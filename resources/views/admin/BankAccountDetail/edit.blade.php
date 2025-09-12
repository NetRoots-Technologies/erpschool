@extends('admin.layouts.main')

@inject('helper', 'App\Helper\helper')

@section('content')

    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Update Account</strong> <span class="float-end"><a
                                    href="{{url('/admin/BankAccountDetail')}}" class="btn btn-primary" type="submit"
                                    value="Save">Back</a></span></div>
                        <div class="card-body">
                            <form method="POST" action="{{route('admin.BankAccountDetail.update',$data->id)}}">
                                {{--                            <form action="{{route('admin.permissions.store')}}" method="post" enctype="multipart/form-data">--}}
                                @csrf
                                {{ method_field('PUT') }}
                                <div class="row">

                                    <div class="col-md-4">
                                        <input type="hidden" name="id" value="{{$data->id}}>
                                            </div>

                                    <div class=" col-md-4">
                                        <label>Bank Name</label>
                                        <select required class="form-control " name="bank_id"
                                                id="exampleFormControlSelect1">
                                            <option value="">Please Select</option>
                                            @foreach($helper::getBanks() as $bank)
                                                <option value="{{$bank['id']}}">{{$bank['bank_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Bank Branch Code</label>
                                        <select required class="form-control " name="branch_code_id"
                                                id="exampleFormControlSelect1">
                                            <option value="">Please Select</option>
                                            @foreach($helper::getBanksBranch() as $bankbranch)
                                                <option
                                                    value="{{$bankbranch['id']}}">{{$bankbranch['branch_code_id']}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="name">Account Title</label>
                                        <input type="decimal" name="account_title" class="form-control"
                                               value="{{$data->account_title}}" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="name">Account No</label>
                                        <input type="text" name="account_no" class="form-control"
                                               value="{{$data->account_no}}" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">

                                        <label for="name">Account Type</label>
                                        <input type="text" name="account_type" class="form-control"
                                               value="{{$data->account_type}}" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">

                                        <label for="name">Phone No</label>
                                        <input type="text" name="phone_no" class="form-control"
                                               value="{{$data->phone_no}}" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">

                                        <label for="name">Address</label>
                                        <input type="text" name="address" class="form-control"
                                               value="{{$data->address}}" autocomplete="off">
                                    </div>

                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Update Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
