@extends('admin.layouts.main')
@section('title')
    Create Marketing Video
@stop
@section('content')
    <div class="container w-100 ">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-header bg-light">
                        <h3 class="text-22 text-midnight text-bold mb-4">Create Marketing Video </h3>
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
                        <form method="post" action="{!! route('admin.marketing_video.store') !!}"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="row ">
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Name</label>
                                        </div>
                                        <input required type="text" class="form-control" name="name">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Video Id</label>
                                        </div>
                                        <input required type="text" class="form-control" name="video_id">
                                    </div>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Description</label>
                                        </div>
                                        <textarea required type="text" class="form-control"
                                                  name="description"></textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="row  ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Video Link</label>
                                        </div>
                                        <input type="text" name="video_link" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row  ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Status</label>
                                        </div>
                                        <select class="select2 form-control" name="status" required>
                                            <option selected disabled style="color: gray">Select Status</option>
                                            <option value="1">Publish</option>
                                            <option value="0">Un-Publish</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row text-right">
                                <div class="form-group col-12 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                    <a href="{!! route('admin.marketing_video.index') !!}"
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
