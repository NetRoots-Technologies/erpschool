/**
 * Created by ertiza.ejaz on 26/09/2018.
 */

//== Class definition
var FormControls = function () {
    //== Private functions
    $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
    var token = $("input[name=_token]").val();
    var baseFunction = function () {
        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                '#title': {
                    required: true
                },
                '#date': {
                    required: true
                },
                '#working_length': {
                    required: true
                }
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            }
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