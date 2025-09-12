/**
 * Created by mustafa.mughal on 30/01/2018.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        changeDuration();

        $('#shift_start_time, #shift_end_time, #break_start_time, #break_end_time').change(function () {
            changeDuration();
        });

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                name: {
                    required: true
                },
                hours_per_day: {
                    required: true,
                    min: 0.25
                },
                start_time: {
                    required: true,
                },
                end_time: {
                    required: true,
                },
                working_hours_per_day: {
                    required: true,
                    min: 0.25
                },
                break_hours_per_day: {
                    required: true,
                    min: 0
                },
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }
    
    var changeDuration = function () {
        // Working Times
        var shift_start_time = '';
        var shift_end_time = '';
        var working_hours_per_day = 0;

        // Break Times
        var break_start_time = '';
        var break_end_time = '';
        var break_hours_per_day = 0;

        // Working Hours Calculation
        if($('#shift_start_time').val() != '' && $('#shift_end_time').val() != '') {
            shift_start_time = $('#shift_start_time').val();
            shift_end_time = $('#shift_end_time').val();
            working_hours_per_day = ( new Date("1970-1-1 " + shift_end_time) - new Date("1970-1-1 " + shift_start_time) ) / 1000 / 60 / 60;
        } else {
            working_hours_per_day = 0;
        }

        // Break Hours Calculation
        if($('#break_start_time').val() != '' && $('#break_end_time').val() != '') {
            break_start_time = $('#break_start_time').val();
            break_end_time = $('#break_end_time').val();
            break_hours_per_day = ( new Date("1970-1-1 " + break_end_time) - new Date("1970-1-1 " + break_start_time) ) / 1000 / 60 / 60;
        } else {
            break_hours_per_day = 0;
        }

        if(working_hours_per_day < 0){
            alert('Please select start time less than end time');
            $('#shift_start_time').val('');
            $('#shift_end_time').val('');
            $('#working_hours_per_day').val(0);
            return false;
        }else{
            $('#working_hours_per_day').val(working_hours_per_day - break_hours_per_day);
            $('#break_hours_per_day').val(break_hours_per_day);
            $('#hours_per_day').val(working_hours_per_day);
        }

    }


    return {
        // public functions
        init: function() {
            baseFunction();
        }
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});