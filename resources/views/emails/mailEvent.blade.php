@extends('emails.main')
@section('content')
    <div>

        @if($data['type']=='advance')
            <h2 style="padding: 7px 0px; margin-bottom: 10px; background-color: #F9F9F9; text-align: center;">
                Registered Student Email
            </h2>

            <div style="color: black">
                <p>
                    Dear {!! $data['name'] !!} ,
                </p>
                <p>
                    We are glad that now officially, you are a part of Onezcommerce. We welcome you to the training
                    course
                    of "{!! $data['course_name'] !!}." in the session of
                    "{!! $data['session_name'] !!}". We ensure that you will be skilled and trained by our
                    exceptional team. We will
                    help and guide you at every step because success is obligatory on us. Below are some details that
                    are
                    essential for you to note and keep safe.
                </p>
            </div>
            <label><b> You can login into LMS using these credentials : </b></label>
            <br><br>
            <label><b><a href="https://portal.onezcommerce.com/">Student Portal </a></b> </label><br><br>
            <label><b><u> LMS User Email: </u></b> {!! $data['email'] !!}</label><br><br>
            <label><b><u> LMS Password:</u></b> {!! $data['password'] !!}</label><br><br>

            <b>Thankyou for advance payment: {!! $data['advance'] !!}</b>

            <br/>
            <div style="color: black">

                <p>
                    <b>Please Note </b>:
                    LMS is for student convenience; you can find all the course data on LMS even after the course
                    ends. The
                    LMS will expire within a year of your enrolment. Make sure that you remember the USER ID. You
                    must
                    change your password after logging in for the first time.
                    You can make new password in your profile.</p>
            </div>
        @else

            <h2 style="padding: 7px 0px; margin-bottom: 10px; background-color: #F9F9F9; text-align: center;">
                Instalment Email
            </h2>

            <div style="color: black">
                <p>
                    Dear {!! $data['name'] !!} ,
                </p>
                <p>
                    This is a confirmation email form Onezcommerce that your   instalment of your fee is paid.
                </p>
            </div>



            <b>Thankyou for installment payment: {!! $data['advance'] !!}</b>

            <br/>




            <div style="color: black">

                <p>
                    <b>Please Note </b>:
                    LMS is for student convenience; you can find all the course data on LMS even after the
                    course
                    ends. The
                    LMS will expire within a year of your enrolment. Make sure that you remember the USER ID.
                    </p>
            </div>




        @endif
    </div>
@endsection
