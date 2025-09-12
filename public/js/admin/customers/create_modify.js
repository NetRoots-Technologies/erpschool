/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        $('.select2').select2();

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                branch_id: {
                    required: true
                },
                employee_id: {
                    required: true
                },
                name: {
                    required: true
                },
                mobile_1: {
                    required: true,
                    number: true
                },
                phone_1: {
                    required: true,
                    number: true
                },
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "branch_id") {
                    error.insertAfter($('#branch_id_handler'));
                } else if (element.attr("name") == "employee_id") {
                    error.insertAfter($('#employee_id_handler'));
                } else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
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