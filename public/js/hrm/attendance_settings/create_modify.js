/**
 * Created by mustafa.mughal on 30/01/2018.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

      //  applyCondition();

        applyMaxValue();



        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                name: {
                    required: true
                },
                permitted_days: {
                    required: true,
                    number: true,
                    min: 0,
                },
                allowed_number: {
                    required: '#condition:checked',
                    number: true,
                    min: 0
                },
                allowed_type: {
                    required: '#condition:checked',
                }
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

    var applyCondition = function () {
        $('.allowed').hide();
        if($('#condition:checked').length) {
            $('.allowed').show();
        } else {
            $('#allowed_number').val('');
            $('#allowed_type').val(1);
        }
    }

    var applyMaxValue = function () {
        // if($('#permitted_days').val() != '') {
        //     $('#allowed_number').attr('max', $('#permitted_days').val());
        //     if($('#allowed_number').val() != '' && (parseInt($('#permitted_days').val()) < parseInt($('#allowed_number').val()))) {
        //         $('#allowed_number').val('');
        //     }
        // }
    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        applyMaxValue: applyMaxValue,
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});