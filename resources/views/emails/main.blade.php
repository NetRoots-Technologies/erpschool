<!DOCTYPE html>

<html class="loading" lang="en" data-textdirection="ltr">

<head>

</head>

<body>


<style>
    .tilte {
        font-weight: bold;
        margin-bottom: 10px;
        color: #111111;
        width: 100%;
    }

    .text_mail {

        color: #999999;
    }

    .img-h {
        max-height: 60px;
    }

    .mail-bdy p {
        font-size: 14px;
        color: #999;
    }

    .im {
        color: #999 !important;
    }


</style>
<style>
    label {
        color: #000000 !important;
    }
</style>
<div style="background-color: #f5f8fa !important; width: 100%; height: 100%; padding: 20px 0px;">

    <div style="width: 600px; margin: 0 auto; background-color: #FFF;">


        <div style="padding:10px; text-align: center;background-color: #395da9" class="hdr-logo">

            {{--            <img style="max-height: 60px;" src="{!! asset('public/images/ONEZ-LOGO.png') !!}" class="img-h"/>--}}
            <img style="max-height: 60px;" src="https://www.onezcommerce.com/public/web_dist/assets/img/Login-page.png"
                 class="img-h"/>

        </div>

        <div style="padding: 9px 18px" class="mail-bdy">

            @yield('content')
        </div>


        <div
            style="padding:15px 0px;  background-color: #111; color:#ffffff; font-family:Helvetica; font-size:12px; text-align:center; font-style: italic; width: 600px; margin: 0 auto;">

            For any query, please email us at inquiry@onezcommerce.com Thank you


        </div>


    </div>

</div>


</body>
<!-- END: Body-->
</html>
