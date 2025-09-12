@extends('admin.layouts.main')
@section('title')
    Show Marketing Notification
@stop
@section('content')
    <div class="container w-100 ">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-header bg-light">

                        <h3 class="text-22 text-midnight text-bold mb-4">Show Marketing Notification </h3>
{{--                        <a class="bg-danger ">Go back</a>--}}
                    </div>
                    <div class="card-body">



                            {{--                            //title--}}
                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Notification Title</label>
                                        </div>
                                        <input  type="text" class="form-control"
                                               value="{!! $ad->notification_title !!}" readonly
                                               name="notification_title">
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label> Card title</label>
                                        </div>
                                        <textarea id="card_title" disabled type="text" name="notification_description"
                                                  readonly     class="form-control " > {!! $ad->notification_description !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label> Notification Image</label>
                                        </div>
                                        <img src="{{url($ad->notification_image)}}" width="100px" height="100px">
                                    </div>
                                </div>
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


@endsection
