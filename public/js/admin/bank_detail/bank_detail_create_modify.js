/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        //$('.select2').select2();
        //$('#employee').select2();
//console.log("Hello");
        $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {

                bank_name: {
                    required: true,
                },
                branch_code: {
                    required: true
                },
                account_no: {
                    required: true
                },

                phone_no: {
                    required: true,
                    integer: true
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

            errorPlacement: function (error, element) {
                if (element.attr("name") === "bank_name") {
                    error.insertAfter($('#bank_name_id'));
                }else {
                    error.insertAfter(element);
                }
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
    $(document).on('click','#orderType',function(){
        var orderval = $(this).val();
        if(orderval === '1'){
            $('.employee').hide();
        }else{
            $('.employee').show();
        }
    });
});