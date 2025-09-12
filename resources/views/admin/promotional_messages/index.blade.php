@extends('admin.layouts.main')
@section('title')
    Promotional Messages
@stop
@section('content')




    <div class="container w-100 ">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-header bg-light">
                        <h3 class="text-22 text-midnight text-bold mb-4">Send Message </h3>
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
                        <form id="message_form">
                            @csrf

                            <div class="row ">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="course"><b>Student Selection<span style="color:red">*</span></b></label>
                                            <select name="for_all" class="  form-control"
                                                    id="for_all">
                                                <option value="" selected>Select option</option>
                                                <option value="for_all">Send to all Students</option>
                                                @foreach ($courses as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <input hidden name="students[]" value="" id="hidden_students">
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-label">
                                            <label><b> Message<span style="color:red">*</span></b></label>
                                        </div>
                                        <textarea id="message_box" type="text" name="message"
                                                  class="form-control " cols="30" required> </textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="row  text-right">
                                <div class="form-group col-12 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary">Send</button>
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
    <script>
        $("#message_form").submit(function (e) {
            e.preventDefault();

            var items = $('#for_all').val();
            var message = $('#message_box').val();

            if (items == "for_all") {
                $.ajax({
                    method: 'POST',
                    url: "{{ route('admin.get_student') }}",
                    data: {

                        "message": message,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        alert('Message Sent Successfully');
                        $('#for_all').val(null);
                        $('#message_box').val(null);

                    }
                });
            } else {

                $.ajax({
                    method: 'POST',
                    url: "{{ route('admin.get_student_with_course') }}",
                    data: {

                        "course_id": items,
                        "message": message,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (response) {
                        alert('Message Sent Successfully');
                        $('#for_all').val(null);
                        $('#message_box').val(null);

                    }
                });
            }
        });

    </script>
@endsection
