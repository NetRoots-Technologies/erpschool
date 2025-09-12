@extends('admin.layouts.main')
@section('title')
    Create Marketing Notification
@stop
@section('content')
    <div class="container w-100 ">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-header bg-light">
                        <h3 class="text-22 text-midnight text-bold mb-4">Create Marketing Notification </h3>
                    </div>
                    <div class="card-body ">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="post" action="{!! route('admin.marketing_notification.store') !!}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Notification Title</label>
                                        </div>
                                        <input required type="text" class="form-control" name="notification_title">
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Notification Description</label>
                                        </div>
                                        <textarea id="card_title" type="text" name="notification_description"
                                                  class="form-control " required> </textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row  ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Notification Image</label>
                                        </div>
                                        <input type="file" accept="image/*" name="notification_image" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row text-right">
                                <div class="form-group col-12 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                    <a href="{!! route('admin.marketing_notification.index') !!}"
                                       class=" btn btn-sm btn-danger">Cancel </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="spinner-border d-none" id="#loader" role="status">
        <span class="sr-only">Loading...</span>
    </div>
@stop
@section('css')

@endsection
@section('js')

@endsection
