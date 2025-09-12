@inject('helper', 'App\Helper\helper')
@extends('admin.layouts.main')


@section('content')
    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Edit Item</strong> <span class="float-end"><a
                                    href="{{route('admin.items.index')}}" class="btn btn-primary" type="button"
                                    value="Save">Back</a></span>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.items.update',$item->id)}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                {{ method_field('PUT') }}
                                @include('admin.items.form')
                                <button class="btn btn-danger" type="submit" value="Save">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="{{$item['sub_category_id']}}" id="sub_cat_val">
@endsection
@section('javascript')
    <script src="{{ url('/js/items.js') }}" type="text/javascript"></script>
    <script>
        // console.log('ok');
        // $('.select3').select2();
    </script>
    <script>

        // console.log('ok');
        // // $('.allow_Users').prop('checked',true);
        // checkMyModule = function (thisOBj, class_1) {
        //     console.log(thisOBj);
        //     if (thisOBj.is(":checked")) {
        //         $('.' + class_1).prop('checked', true);
        //
        //     } else {
        //         $('.' + class_1).prop('checked', false);
        //     }
        //
        // }

    </script>
@endsection
