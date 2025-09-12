/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition


var FormControls = function () {
    //== Private functions

    var baseFunction = function () {


        //$(".datepicker").datepicker({ format: 'yyyy-mm-dd' });

        $('#sal_date').datetimepicker({
            format: 'YYYY-MM-DD H:m', //format: 'DD-MM-YYYY H:m:s A',
            sideBySide: true
        });
        $('#valid_upto').datetimepicker({
            format: 'YYYY-MM-DD H:m',
            sideBySide: true

        });


        // $('input[name="sal_date"]').daterangepicker({
        //     timePicker: true,
        //     timePickerIncrement: 30,
        //     locale: {
        //         format: 'DD/MM/YYYY h:mm A'
        //     }
        // });


        $('#branch_id').select2();
        $('#department_id').select2();

        $('#employee_id').select2();
        $('#customer_id').select2();
        $('#stock_item_id').select2();
        $('#sup_id').select2();

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
                description: {
                    required: true
                },
                remarks: {
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
                sup_id: {
                    required: true
                },
                tax: {
                    required: true,
                    number: true,
                    max:100,
                },
                gst: {
                    required: true,
                    number: true,
                    max:100,
                },
                shipping_charges: {
                    required: true,
                    number: true,
                },
                total_balance: {
                    required: true,
                    number: true,
                },
                total_amount: {
                    required: true,
                    number: true,
                },
                discount: {
                    required: true,
                    number: true,
                    max:100,
                },
                final_amount: {
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

