@extends('admin.layouts.main')

@section('title')
    Session Video Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4"> Create Session Video</h3>
                        <div class="row    mt-4 mb-4 ">

                            <div class="col-12 text-right">
                                <a href="{!! route('admin.session_videos',$id)!!}" class="btn btn-primary btn-sm ">
                                    Back </a>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{!! route('admin.video.store') !!}" enctype="multipart/form-data"
                              id="form_validation" autocomplete="off" method="post">
                            <div class="w-100">

                                @csrf
                                <div class="box-body" style="margin-top:50px;">
                                    <div class="row mt-2">
                                        <div class="col-lg-8">
                                            <label for="course_id"> Session <b>*</b> </label>

                                            <select required name="session_id" id="course_id"
                                                    class="select2 form-control">
                                                <option value="">Select Session</option>


                                                <option @if(isset($sessions))  selected @endif

                                                value="{{$sessions->id}}">{{$sessions->title}}</option>

                                            </select>
                                        </div>

                                    </div>

                                    <div class="row mt-2" id="videos">
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <h3>Add Videos</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">


                                            <table class="table table-bordered" id="dynamicAddRemove">
                                                <tr>
                                                    <th>Video Name</th>
                                                    <th>VIDEO ID</th>
                                                    <th>VIDEO DESCRIPTION</th>
                                                    <th>VIDEO CATEGORY</th>
                                                    <th width="12%">Action</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="name[]" placeholder="Enter name"
                                                               class="form-control" value="{{old('name')}}"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="video_id[]"
                                                               required placeholder="Enter video ID"
                                                               class="form-control" value="{{old('video_id')}}"/>
                                                    </td>
                                                    <td>
                                                        <textarea type="text" name="video_description[]"
                                                                  required placeholder="Enter video description"
                                                                  class="form-control"
                                                        ></textarea>
                                                    </td>

                                                    <td>
                                                        <select type="text" name="video_categories_id[]"
                                                                required id="categorySelect"
                                                                class="form-control"
                                                                value="{{old('video_categories_id')}}">
                                                            <option value="">Select Video Category</option>
                                                            @foreach($video_categories as $item)
                                                                <option
                                                                    value="{!! $item->id !!}">{!! $item->name !!}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" name="add" id="add-btn"
                                                                class="btn btn-success" style="margin-right: -55px;">Add
                                                            More
                                                        </button>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>

                                    </div>
                                </div>

                                <hr style="background-color: darkgray">
                                <div class="row mt-8 mb-3">
                                    <div class="col-12">
                                        <div class="form-group text-right">

                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                            <a href="{!! route('admin.video.index') !!}"
                                               class=" btn btn-sm btn-danger">Cancel </a>
                                        </div>
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
    <script type="text/javascript">

        $("#course_id").change(function () {

            $('#videos').show();

        });
    </script>
    <script type="text/javascript">
        var i = 0;
        $("#add-btn").click(function () {
            ++i;
            $("#dynamicAddRemove").append(
                '<tr>' +

                '<td>'
                +
                '<input type="text" name="name[]" placeholder="Enter name" class="form-control"/>'
                +
                '</td>'
                +
                '<td>'
                +
                '<input type="text" name="video_id[]" placeholder="Enter video ID" class="form-control"  />'
                +
                '</td>'
                +
                '<td>'
                +
                '<textarea type="text" name="video_description[]" placeholder="Enter video description" class="form-control">' + '</textarea>'
                +
                '</td>'
                + '<td>'
                +
                '   <select type="text" name="video_categories_id[]"\n' +
                '                                                                required id="categorySelect"\n' +
                '                                                                class="form-control"\n' +
                '                                                                value="{{old('video_categories_id')}}">\n' +
                '                                                            <option value="">Select Video Category</option>\n' +
                '                                                            @foreach($video_categories as $item)\n' +
                '                                                                <option\n' +
                '                                                                    value="{!! $item->id !!}">{!! $item->name !!}</option>\n' +
                '                                                            @endforeach\n' +
                '                                                        </select>'
                +
                '</td>'
                +

                '<td>'
                +
                '<button type="button" class="btn btn-danger remove-tr">Remove</button>'
                +
                '</td>'
                +
                '</tr>'
            )
            ;


        });
        $(document).on('click', '.remove-tr', function () {
            $(this).parents('tr').remove();
        });
    </script>

@endsection

