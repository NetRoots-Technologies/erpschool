@extends('admin.layouts.main')
@section('title')
    Show Marketing Banner Ad
@stop
@section('content')
    <div class="container w-100 ">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-header bg-light">

                        <h3 class="text-22 text-midnight text-bold mb-4">Show Marketing Banner Ad </h3>
{{--                        <a class="bg-danger ">Go back</a>--}}
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
                        <form method="post"
                              enctype="multipart/form-data">

                            {{--                            //title--}}
                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label>Banner Title</label>
                                        </div>
                                        <input required type="text" class="form-control"
                                               value="{!! $ad->banner_title !!}" readonly
                                               name="banner_title">
                                    </div>
                                </div>
                            </div>

                            <div class="row ">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label> Card title</label>
                                        </div>
                                        <textarea id="card_title" type="text" name="banner_description"
                                                  readonly     class="form-control " required> {!! $ad->banner_description !!}</textarea>
                                    </div>
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
