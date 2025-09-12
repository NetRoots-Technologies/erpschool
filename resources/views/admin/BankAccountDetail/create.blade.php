@extends('admin.layouts.main')

@inject('helper', 'App\Helper\helper')
@section('content')
    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Add Account</strong> <span class="float-end"><a
                                    href="{{url('/admin/BankAccountDetail')}}" class="btn btn-primary" type="submit"
                                    value="Save">Back</a></span>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{route('admin.BankAccountDetail.store')}}">
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
                                    </div>

                                    <div class="col-md-4">
                                        <label>Branch Code</label>
                                        <select class="form-control select2" name="branch_code_id" id="exampleFormControlSelect1">
                                            <option value="0">Select Bank Branch</option>
                                            @foreach($helper::getBanksBranch() as $bankbranch)
                                                <option value="{{$bankbranch['id']}}">{{$bankbranch['branch_code_id']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="name">Account Title</label>
                                        <input type="text" name="account_title" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="name">Account No</label>
                                        <input type="text" name="account_no" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="name">Account Type</label>
                                        <input type="text" name="account_type" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="name">Phone No</label>
                                        <input type="text" name="phone_no" class="form-control" autocomplete="off">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="name">Address</label>
                                        <input type="text" name="address" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                                <br>
                                <button type="submit" class="btn btn-primary">Add Account Detail</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


