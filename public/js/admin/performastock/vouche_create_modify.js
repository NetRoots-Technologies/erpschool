/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition


var FormControls = function () {
    //== Private functions

    var baseFunction = function () {


        //$(".datepicker").datepicker({ format: 'yyyy-mm-dd' });

        $('#sal_date').datetimepicker({
            format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
            sideBySide: true
        });
        $('#valid_upto').datetimepicker({
            format: 'YYYY-MM-DD',
            sideBySide: true
        });

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                customer_id: {
                    required: true
                },
                sal_date: {
                    required: true
                },
                valid_upto: {
                    required: true
                },
                sup_id: {
                    required: true
                },
                stock_item_id: {
                    required: true
                },
                employee_id: {
                    required: true
                },
                branch_id: {
                    required: true
                },
                tax: {
                    required: true,
                    number: true,
                },
                gst: {
                    required: true,
                    number: true,
                    max:100,
                },
                discount: {
                    required: true,
                    number: true,
                },
                shipping_charges: {
                    required: true,
                    number: true,
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

