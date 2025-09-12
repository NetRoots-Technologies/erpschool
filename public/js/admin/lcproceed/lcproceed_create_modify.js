/**
 * Created by zahra batool on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {
        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                insurance: {
                    required: true,
                    number:true
                },
                freight: {
                    required: true,
                    number:true
                },
                profit_investment: {
                    required: true,
                    number:true
                },
                murahba_profit: {
                    required: true,
                    number:true
                },
                // landing_cost:{required: true},
                // cif_value:{required: true},
                // custom_duty:{required: true},
                // additional_tax:{required: true},
                // sales_tax:{required: true},
                // additional_duty:{required: true},
                // income_tax:{required: true},
                // sindh_excise_duty:{required: true},
                // clearing_charges:{required: true},
                // mov_frm:{required: true},
                // fob_freight:{required: true},
                // bnk_comm:{required: true},

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