/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition


//alert("supplier validation is on working");

var FormControls = function () {
    //== Private functions

    var baseFunction = function () {
        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                sup_name: {
                    required: true,
                }
                // sup_address: {
                //     required: true
                // },
                // sup_email: {
                //     email: true,
                //     required: true
                // },
                // sup_mobile: {
                //     required: true ,
                //     integer : true
                // },
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
//             submitHandler: function (form) {
//                 $(".btn-danger").attr("disabled", true);
//                 var attr = $('.btn-danger').attr('disabled');
//                 if (typeof attr !== typeof undefined && attr !== false) {
//                     // Element has this attribute
//                     form[0].submit(); // submit the form
//                 }else {
// alert('ok');
//                 }
//
//             }
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
//     $(".btn-danger").click(function () {
//         $(".btn-danger").attr("disabled", true);
//         $("#validation-form").submit();
// //                return true;
//     });
});