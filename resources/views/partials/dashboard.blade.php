@extends('layouts.app')
<?php $per = PermissionHelper::getUserPermissions();?>
@section('breadcrumbs')
<section class="content-header" style="padding: 10px 15px !important;">
    <h1>Dashboard </h1>
</section>
@stop

@section('content')
<div class="box box-primary">
    <!-- /.box-header -->
    <div class="panel-body pad table-responsive">
        @if(isset($Candidate))
        <table class="table table-bordered table-stripped">
            <tr>
                <th>Name</th>
                <td>{{$Candidate->name}}</td>
            </tr>
            <tr>
                <th>Section</th>
                <td>{{$Candidate->section}}</td>
            </tr>
            <tr>
                <th>Education</th>
                <td>{{$Candidate->education}}</td>
            </tr>
            <tr>
                <th>Experience</th>
                <td>{{$Candidate->experience}}</td>
            </tr>

            <tr>
                <th>Expected Salary</th>
                <td>{{$Candidate->expected_salary}}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{$Candidate->phone}}</td>
            </tr>

            <tr>
                <th width="30%">Expected joining Date</th>
                <td>{{$Candidate->expected_joining}}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{$Candidate->email}}</td>
            </tr>
            @if($data != 'NA' && $status != 'Selected' && $status != 'Interview-Taken')
            <tr style="background-color: yellow">
                <th>Interview Scheduled Time</th>
                <td>{{$data->scheduled_time}}</td>
            </tr>
            <tr style="background-color: yellow">
                <th>Required Documents</th>
                <td>{{$data->required_doc}}</td>
            </tr>
            @endif
            <tr>
                <th>Status</th>
                <td>
                    @if($status == 'Pending')
                    <button class="badge btn-warning" style="background-color: yellow; color: 
                                black">Pending</button>
                    @elseif($status == 'Approved')

                    <button class="badge btn-success" style="background-color: green">Aproved</button>
                    @elseif($status == 'Rejected')

                    <button class="badge btn-danger" style="background-color: red">Rejected</button>
                    @elseif($status == 'Selected')

                    <button class="badge btn-success" style="background-color: green">Congrations! You're
                        Hired</button>&nbsp;&nbsp;<a data-toggle="modal"
                        data-target="#exampleModal1{{$Candidate->id}}"><span><button
                                class="btn btn-primary">Acknowledged</button></span></a>
                    @elseif($status == 'Interview-Pending')

                    <button class="badge btn-default">Interview-Pending</button>
                    @elseif($status == 'Interview-Taken')

                    <button class="badge btn-default">Interview-Taken</button>
                    @elseif($status == 'Acknowledged')

                    <button class="badge btn-default">Profile Updated Successfully</button>

                    @endif
                </td>
                <div class="modal fade" id="exampleModal1{{$Candidate->id}}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title" id="exampleModalLabel">Update Your Profile</h2>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                {!! Form::open(['route' => 'candidate-form.store','method' => 'POST', 'class' =>
                                'form-horizontal' ,"enctype" => "multipart/form-data"]) !!}
                                <input type="hidden" name="profile_updated" value="profile_updated">
                                <input type="hidden" name="candidate_id" value="{{$Candidate->id}}">
                                <div class="form-group">
                                    <label class="col-form-label">CNIC</label>
                                    <input type="file" name="cnic" class="form-control" required="required">
                                </div>
                                <div style="margin-top: 20px">

                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">DOMICILE</label>
                                    <input type="file" name="domicile" class="form-control">
                                </div>
                                <div style="margin-top: 20px">

                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">PASSPORT</label>
                                    <input type="file" name="passport" class="form-control">
                                </div>
                                <div style="margin-top: 20px">

                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">PASSPORT SIZE PHOTO</label>
                                    <input type="file" name="passport_size_photo" class="form-control">
                                </div>
                                <div style="margin-top: 20px">

                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">DEGREE</label>
                                    <input type="file" name="degree" class="form-control">
                                </div>
                                <div style="margin-top: 20px">

                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">AWARDS</label>
                                    <input type="file" name="awards" class="form-control">
                                </div>
                                <div style="margin-top: 20px">

                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">RECOMMENDATION LETTER</label>
                                    <input type="file" name="recommendation_letter" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">EXPERIENCE LETTER</label>
                                    <input type="file" name="experience_letter" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            </tr>
        </table>
        @endif

        @if(in_array('employees_manage', $per))
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ number_format($Counts['employees']) }}</h3>
                    <p>Employees</p>
                </div>
                <div class="icon"><i class="fa fa-gear"></i></div>
                <a href="{{ route('admin.employees.index') }}" class="small-box-footer">View details <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endif
        @if(in_array('customers_manage', $per))
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ number_format($Counts['customers']) }}</h3>
                    <p>Customers</p>
                </div>
                <div class="icon"><i class="fa fa-gear"></i></div>
                <a href="{{ route('admin.customers.index') }}" class="small-box-footer">View details <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endif
        @if(in_array('products_manage', $per))
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3></h3>
                    <p>Products</p>
                </div>
                <div class="icon"><i class="fa fa-gear"></i></div>
                <a href="" class="small-box-footer">View details <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endif
        @if(in_array('entries_manage', $per))
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ number_format($Counts['entries']) }}</h3>
                    <p>Entries</p>
                </div>
                <div class="icon"><i class="fa fa-book"></i></div>
                <a href="{{ route('admin.entries.index') }}" class="small-box-footer">View details <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        @endif

    </div>
</div>




@stop