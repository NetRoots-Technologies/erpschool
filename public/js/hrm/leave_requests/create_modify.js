/**
 * Created by mustafa.mughal on 30/01/2018.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        $("#start_date").datepicker({
            format: 'yyyy-mm-dd',
            onSelect: function(dateText) {
                $(this).change();
            }
        }).on("change", function() {
            loadDays();
        });

        $("#end_date").datepicker({
            format: 'yyyy-mm-dd',
            onSelect: function(dateText) {
                $(this).change();
            },
        }).on("change", function() {
            loadDays();
        });

        $('#employee_id').select2().on('change', function() {
                loadEmployeeEntitlements();
        });

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                employee_id: {
                    required: true
                },
                leave_type_id: {
                    required: true
                },
                work_shift_id: {
                    required: true
                },
                start_date: {
                    required: true
                },
                end_date: {
                    required: true
                },
                total_days: {
                    required: true,
                    min: 1
                },
                single_hours_duration: {
                    min: 0.25
                },
                all_days_hours_duration: {
                    min: 0.25
                },
                starting_hours_duration: {
                    min: 0.25
                },
                ending_hours_duration: {
                    min: 0.25
                }
            },
            messages: {
                total_days: {
                    required: 'Required',
                    min: 'Start Date must less than or equal to End Date.'
                },
                single_hours_duration: {
                    min: 'Start must less than End.'
                },
                all_days_hours_duration: {
                    min: 'Start must less than End.'
                },
                starting_hours_duration: {
                    min: 'Start must less than End.'
                },
                ending_hours_duration: {
                    min: 'Start must less than End.'
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "employee_id") {
                    error.insertAfter($('#employee_id_handler'));
                } else if (element.attr("name") == "leave_type_id") {
                    error.insertAfter($('#leave_type_id_handler'));
                } else if (element.attr("name") == "work_shift_id") {
                    error.insertAfter($('#work_shift_id_handler'));
                } else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });

        // Load Default Functions
        loadDays();
        loadEmployeeEntitlements();
    }
    
    var loadDays = function () {
        // Hide all Divs
        $('#single').hide();
        $('#partial').hide();

        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();


        var leave_type_id = $('#leave_type_id').val();

        var startObj = new Date(start_date);
        var endObj = new Date(end_date);
        var start_month = (startObj.getMonth()) +1;
        var end_month = (endObj.getMonth()) +1;
        console.log('Month : ' , start_month);
        if(leave_type_id == 4){
            if(start_month == 6 || start_month == 12 || end_month == 6 || end_month == 12){
                alert('You cannot apply for annual leaves in June and December');
                $('#end_date').val('');
                $('#start_date').val('');
                $('#total_days').val('');

                return false;
            }
        }


        if(start_date != '' && end_date == '') {
            $('#end_date').val(start_date);
            end_date = start_date;
        } else if(start_date == '' && end_date != '') {
            $('#start_date').val(end_date);
            start_date = end_date;
        }
        var start = moment(start_date, 'YYYY-MM-DD');
        var end = moment(end_date, 'YYYY-MM-DD');
        // var start = moment([start_date.split('-')[0], start_date.split('-')[1], start_date.split('-')[2]]);
        // var end = moment([end_date.split('-')[0], end_date.split('-')[1], end_date.split('-')[2]]);
       // end_date.diff(start_date);
        var difference = end.diff(start, 'days');

        $('#total_days').val(parseInt(difference) + 1);

        if(difference == '0') {
            $('#single').show();
            singleProcess();
        } else if(difference > 0) {
            $('#partial').show();
            partialProcess();
        }
    }

    var durationProcess = function (process_name) {
        $('.' + process_name + '_shift').hide();
        $('.' + process_name + '_hours_start').hide();
        $('.' + process_name + '_hours_end').hide();
        $('.' + process_name + '_hours_duration').hide();

        if($('#' + process_name + '_duration').val() == 'half_day') {
            $('.' + process_name + '_shift').show();
        } else if($('#' + process_name + '_duration').val() == 'specify_time') {
            $('.' + process_name + '_hours_start').show();
            $('.' + process_name + '_hours_end').show();
            $('.' + process_name + '_hours_duration').show();
        }
    }
    
    var hoursProcess = function (process_name) {
        var start = $('#' + process_name + '_hours_start').val();
        var end = $('#' + process_name + '_hours_end').val();

        var duration = ( new Date("1970-1-1 " + end) - new Date("1970-1-1 " + start) ) / 1000 / 60 / 60;
        $('#' + process_name + '_hours_duration').val(duration);
    }

    // Single Date Process
    var singleProcess = function () {
        durationProcess('single');
        hoursProcess('single');
    }

    // Partial Dates Process
    var partialProcess = function () {
        $('#all_days').hide();
        $('#starting').hide();
        $('#ending').hide();
        if($('#partial_days').val() == 'all') {
            $('#all_days').show();
            allDaysProcess();
        } else if($('#partial_days').val() == 'start') {
            $('#starting').show();
            startDayProcess();
        } else if($('#partial_days').val() == 'end') {
            $('#ending').show();
            endDayProcess();
        } else if($('#partial_days').val() == 'start_end') {
            $('#starting').show();
            startDayProcess();
            $('#ending').show();
            endDayProcess();
        }

    }

    // All Days Process
    var allDaysProcess = function () {
        durationProcess('all_days');
        hoursProcess('all_days');
    }

    // Start Day Process
    var startDayProcess = function () {
        durationProcess('starting');
        hoursProcess('starting');
    }

    // End Day Process
    var endDayProcess = function () {
        durationProcess('ending');
        hoursProcess('ending');
    }

    var loadEmployeeEntitlements = function () {
        $('#loadEmployeeEntitlements').html('');
        // var employee_id =
        //$('#employee_id').val() != '' &&
        if( $('#leave_type_id').val() != '' && $('#work_shift_id').val() != '') {
            $.ajax({
                url: route('hrm.leave_requests.leave_balance'),
                type: 'get',
                data: {
                    employee_id: $('#employee_id').val(),
                    leave_type_id: $('#leave_type_id').val(),
                },
                success: function( data, textStatus, jQxhr ){
                    $('#loadEmployeeEntitlements').html(data);
                },
                error: function( jqXhr, textStatus, errorThrown ){
                    $('#loadEmployeeEntitlements').html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alert!</h4>Something went worng, please try again later.</div>');
                }
            });
        } else {
            $('#loadEmployeeEntitlements').html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-warning"></i> Alert!</h4>Select an employee, leave type and work shift to view leave balance detail.</div>');
        }
    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        loadEmployeeEntitlements: loadEmployeeEntitlements,
        singleProcess: singleProcess,
        partialProcess: partialProcess,
        allDaysProcess: allDaysProcess,
        startDayProcess: startDayProcess,
        endDayProcess: endDayProcess,
        hoursProcess: hoursProcess,
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});