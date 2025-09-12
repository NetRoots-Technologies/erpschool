/**
 * Created by mustafa.mughal on 30/01/2018.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {
        $('#employee_id').select2({

        });
        // $('#employee_id').select2({
        //     allowClear: true,
        //     placeholder: "Employee",
        //     minimumInputLength: 2,
        //     ajax: {
        //         url: route('hrm.leave_entitlements.employee_search'),
        //         dataType: 'json',
        //         delay: 500,
        //         data: function (params) {
        //             return {
        //                 item: params.term,
        //             };
        //         },
        //         processResults: function (data) {
        //             return {
        //                 results: data
        //             };
        //         },
        //     }
        // });

        $('#leave_type_id').select2();

        $('#entitlement_dates').change(function () {
            applyDates();
        })

        $('#validation-form :checkbox').change(function() {
            applyCondition();
        });

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                leave_type_id: {
                    required: true
                },
                start_date: {
                    required: true
                },
                end_date: {
                    required: true
                },
                entitlement_dates: {
                    required: true
                },
                no_of_days: {
                    required: true,
                    number: true,
                    min: 0,
                },
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
                } else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });

        // Apply Default presets
        applyCondition();
        applyDates();
    }

    var applyCondition = function () {
        $('.employee_id').hide();
        $('.employee').hide();
        if($('#condition:checked').length) {
            $('.employee').show();
        } else {
            $('.employee_id').show();
        }
    }

    var applyDates = function () {
        if($('#entitlement_dates').val() != '') {
            var entitlement_dates = $('#entitlement_dates').val().split('::');
            $('#start_date').val(entitlement_dates[0]);
            $('#end_date').val(entitlement_dates[1]);
        }
    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});