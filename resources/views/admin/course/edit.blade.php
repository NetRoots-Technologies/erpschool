@extends('admin.layouts.main')
@section('title')
    User Edit
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Edit </h3>
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
                            <form action="{!! route('admin.course.update',$courses->id) !!}" enctype="multipart/form-data"
                                  id="form_validation" autocomplete="off" method="post">
                                @csrf
                                @method('put')

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Name</label>
                                            </div>
                                            <input type="text" required class="form-control" value="{!!$courses->name!!}" name="name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Duration</label>
                                            </div>

                                            <select name="course_duration" class="form-control" required>
                                                @if($courses->course_duration == '1 month')
                                                    `<option value="">Select Duration</option>
                                                    <option value="1 month" selected>1 Month</option>
                                                    <option value="2 months">2 Months</option>
                                               `     <option value="3 months">3 Months</option>
                                                    @elseif($courses->course_duration == '2 months')
                                                <option value="">Select Duration</option>
                                                    <option value="1 month">1 Month</option>
                                                    <option value="2 months" selected>2 Months</option>
                                                    <option value="3 months">3 Months</option>
                                                @elseif($courses->course_duration == '3 months')
                                                    <option value="">Select Duration</option>
                                                    <option value="1 month">1 Month</option>
                                                    <option value="2 months" >2 Months</option>
                                                    <option value="3 months"selected>3 Months</option>
                                                @endif

                                            </select>

                                           </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Fee</label>
                                            </div>
                                            <input type="text" required class="form-control" value="{!!$courses->fee!!}" name="fee">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <div class="input-label">
                                                <label>Course Type</label>
                                            </div>
                                            <select class="form-control" name="course_type_id" required="required">
                                                `<option value="">Select Coursetype</option>
                                                @foreach($course_types as $key => $course_type)
                                                    @if($courses->course_type_id==$course_type['id'])
                                                        <option value="{{$course_type['id']}}" selected>{{$course_type['name']}}</option>
                                                    @else
                                                        <option value="{{$course_type['id']}}">{{$course_type['name']}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-5 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            <a href="{!! route('admin.course.index') !!}"
                                            <a href="{!! route('admin.course.index') !!}"
                                               class=" btn btn-sm btn-danger">Cancel </a>
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

    <link rel="stylesheet" href="{{ asset('dist/admin/assets/plugins/dropify/css/dropify.min.css') }}">
@endsection
@section('js')

    <script type="text/javascript" src="{{ asset('dist/admin/assets/plugins/dropify/js/dropify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dist/admin/assets/js/pages/forms/dropify.js') }}"></script>

@endsection


