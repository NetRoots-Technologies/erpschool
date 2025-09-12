@extends('admin.layouts.main')
@section('title')
    Edit Web Registration Banner
@stop
@section('content')
    <div class="container w-100 ">
        <div class="row ">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-header bg-light">
                        <h3 class="text-22 text-midnight text-bold mb-4">Edit Web Registration Banner</h3>
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
                        <form method="post" action="{!! route('admin.web_form_edit_post') !!}"
                              enctype="multipart/form-data">
                            @csrf

                            {{--                            //title--}}
                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label><strong>Banner Image</strong> <span> Dimensions=1349*460</span></label>
                                        </div>
                                        <input type="file" accept="image/*" name="banner_image" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row  text-right">
                                <div class="form-group col-12 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
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
