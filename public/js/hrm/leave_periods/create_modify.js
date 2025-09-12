/**
 * Created by mustafa.mughal on 30/01/2018.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {
        if($('#start_date').val() == '') {
            $('#start_date').val(moment().startOf('year').format("YYYY-MM-DD"));
            $('#end_date').val(moment().endOf('year').format("YYYY-MM-DD"));
        }

        $("#start_date").datepicker({
            format: 'yyyy-mm-dd',
            onSelect: function(dateText) {
                $(this).change();
            }
        }).on("change", function() {
            $('#end_date').val(moment($(this).val(), "YYYY-MM-DD").add(1, 'year').subtract(1,'days').format("YYYY-MM-DD"));
        });


        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
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