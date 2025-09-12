@extends('admin.layouts.main')
@section('title')
    Edit Marketing Notification
@stop
@section('content')
    <div class="container w-100 ">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-header bg-light">
                        <h3 class="text-22 text-midnight text-bold mb-4">Edit Marketing Notification</h3>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="post" action="{!! route('admin.marketing_notification.update',$client->id) !!}"
                              enctype="multipart/form-data">
                            @csrf
                            @method(('put'))
                            {{--                            //title--}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Notification Title</label>
                                        </div>
                                        <input required type="text" class="form-control"
                                               value="{!! $client->notification_title !!}"
                                               name="notification_title">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label> Card title</label>
                                        </div>
                                        <textarea id="card_title" type="text" name="notification_description"
                                                  class="form-control " required> {!! $client->notification_description !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row  m-5">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Notification Image</label>
                                        </div>
                                        <input type="file" accept="image/*" name="notification_image" >
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5 text-right">
                                <div class="form-group col-12 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                    <a href="{!! route('admin.marketing_banner_ad.index') !!}"
                                       class=" btn btn-sm btn-danger">Cancel </a>
                                </div>
                            </div>

                        </form>
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
