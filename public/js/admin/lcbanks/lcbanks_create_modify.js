/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        $('.select2').select2();

        $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {

                transaction_type : {
                    required: true
                },
                profit_investment : {
                    required: true
                },
                pf_no: {
                    required: true
                },
                lc_no: {
                    required: true
                },
                bnk_name: {
                    required: true
                },
                lc_amt:{
                    required:true
                },
                lc_status:{
                    required:true
                },
                dollar_rate:{
                    required:true
                },
                lc_margin:{
                    required:true
                },
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "bnk_name") {
                    error.insertAfter($('#bnk_name_handler'));
                } else if (element.attr("name") == "pf_no[]") {
                    error.insertAfter($('#pf_no_handler'));
                } else if (element.attr("name") == "transaction_type") {
                    error.insertAfter($('#transaction_handler'));
                } else if (element.attr("name") == "delivery_term") {
                    error.insertAfter($('#delivery_term_handler'));
                } else if (element.attr("name") == "lc_type") {
                    error.insertAfter($('#lc_type_handler'));
                }else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
            // submitHandler: function (form) {
            //     form[0].submit(); // submit the form
            // }

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