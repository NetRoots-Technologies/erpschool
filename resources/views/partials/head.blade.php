<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Netroots</title>
<link href="{{ url('public/theme') }}/assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css" rel="stylesheet">
<link href="{{ url('public/theme') }}/assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css" rel="stylesheet">
<link href="{{ url('public/theme') }}/assets/plugins/pickerjs/picker.min.css" rel="stylesheet">

<link rel="stylesheet" type="text/css" media="all" href="{{ url('public') }}/daterangepicker.css" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
		<!--- Favicon -->
		<link rel="icon" href="{{ url('public/theme') }}/assets/img/brand/onezcommerce-logo-blue-1-1.png"/>

		<!--- Icons css -->
		<link href="{{ url('public/theme') }}/assets/css/icons.css" rel="stylesheet">
		<link href="{{ url('public/theme') }}/assets/plugins/mscrollbar/jquery.mCustomScrollbar.css" rel="stylesheet"/>
		<!-- Owl-carousel css-->
<link href="{{ url('public/theme') }}/assets/plugins/owl-carousel/owl.carousel.css" rel="stylesheet"/>

		<!--- Right-sidemenu css -->
		<link href="{{ url('public/theme') }}/assets/plugins/sidebar/sidebar.css" rel="stylesheet">

		<!--- Custom Scroll bar -->
		<link href="{{ url('public/theme') }}/assets/plugins/mscrollbar/jquery.mCustomScrollbar.css" rel="stylesheet"/>
		<link rel="stylesheet" href="https://unpkg.com/multiple-select@1.3.1/dist/multiple-select.min.css">
		<!--- Style css -->
		<link href="{{ url('public/theme') }}/assets/css/style.css" rel="stylesheet">
		<link href="{{ url('public/theme') }}/assets/css/skin-modes.css" rel="stylesheet">
		<link href="{{ url('public/theme') }}/assets/plugins/morris.js/morris.css" rel="stylesheet">
		<!--- Sidemenu css -->
		<link href="{{ url('public/theme') }}/assets/css/sidemenu.css" rel="stylesheet">

		<!--- Animations css -->
		<link href="{{ url('public/theme') }}/assets/css/animate.css" rel="stylesheet">

		<!--- Switcher css -->
		<link href="{{ url('public/theme') }}/assets/switcher/css/switcher.css" rel="stylesheet">
		<link href="{{ url('public/theme') }}/assets/switcher/demo.css" rel="stylesheet">

		{{-- <link rel="stylesheet" href="{{ url('public/adminlte') }}/bower_components/select2/dist/css/select2.min.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	@yield('css')
<style>
*::-webkit-scrollbar {
    width: 14px !important;
}
.ps > .ps__rail-y {
    width: 10px;

}
.row{
  margin-right:0px !important;
}
.tabbable-panel {
  border:1px solid #eee;
  padding: 10px;
}

.tabbable-line > .nav-tabs {
  border: none;
  margin: 0px;
}
.tabbable-line > .nav-tabs > li {
  margin-right: 2px;
  padding:20px;
}
.tabbable-line > .nav-tabs > li > a {
  border: 0;
  margin-right: 0;
  color: #737373;
}
.tabbable-line > .nav-tabs > li > a > i {
  color: #a6a6a6;
}
.tabbable-line > .nav-tabs > li.open, .tabbable-line > .nav-tabs > li:hover {
  border-bottom: 4px solid rgb(80,144,247);
}
.tabbable-line > .nav-tabs > li.open > a, .tabbable-line > .nav-tabs > li:hover > a {
  border: 0;
  background: none !important;
  color: #333333;
}
.tabbable-line > .nav-tabs > li.open > a > i, .tabbable-line > .nav-tabs > li:hover > a > i {
  color: #a6a6a6;
}
.tabbable-line > .nav-tabs > li.open .dropdown-menu, .tabbable-line > .nav-tabs > li:hover .dropdown-menu {
  margin-top: 0px;
}
.tabbable-line > .nav-tabs > li.active {
  border-bottom: 4px solid #32465B;
  position: relative;
}
.tabbable-line > .nav-tabs > li.active > a {
  border: 0;
  color: #333333;
}
.tabbable-line > .nav-tabs > li.active > a > i {
  color: #404040;
}
.tabbable-line > .tab-content {
  margin-top: -3px;
  background-color: #fff;
  border: 0;
  border-top: 1px solid #eee;
  padding: 15px 0;
}
.portlet .tabbable-line > .tab-content {
  padding-bottom: 0;
}
.content-wrapper
{
	margin-top:110px;
}
*::-webkit-scrollbar {
    width: 4px;
    height: 15px;
    transition: .3s background;
}
	</style>





