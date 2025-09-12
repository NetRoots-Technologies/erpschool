@extends('admin.layouts.main')
@section('title')
    Show Marketing Video
@stop
@section('content')
    <div class="container w-100 ">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-header bg-light">
                        <h3 class="text-22 text-midnight text-bold mb-4">Show Marketing Video</h3>
                    </div>
                    <div class="card-body">



                            <div class="row ">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Name</label>
                                        </div>
                                        <input  disabled type="text" value="{{$data->name}}" class="form-control" name="name">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Video Id</label>
                                        </div>
                                        <input disabled  type="text" class="form-control" value="{{$data->video_id}}" name="video_id">
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Description</label>
                                        </div>
                                        <textarea disabled type="text" class="form-control"
                                                  name="description">{{$data->description}}</textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="row  ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Video Link</label>
                                        </div>
                                        <input disabled type="text" name="video_link" class="form-control" value="{{$data->video_link}}" >
                                    </div>
                                </div>
                            </div>

                            <div class="row  ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Status</label>
                                        </div>

                                            @if($data->status == 1)
                                                <option selected disabled style="color: gray" value="1">Publish</option>
                                            @else
                                                <option selected disabled style="color: gray" value="0">Un-Publish</option>
                                            @endif


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
