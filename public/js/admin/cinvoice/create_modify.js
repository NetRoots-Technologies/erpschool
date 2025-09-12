/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd' //format: 'DD-MM-YYYY H:m:s A',
        });
        $('#sup_id').select2();

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                po_number: {
                    required: true
                },
                po_date: {
                    required: true
                },
                sup_id: {
                    required: true
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
            // submitHandler: function (form) {
            //     $("#submit").prop("disabled", true);
            //    form[0].submit(); // submit the form
            //
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

$(document).ready(function() {
    FormControls.init();

    $(document).on('keyup','.calculate',function () {
        var calculated_amount = '0';
        var rowId = $(this).attr('data-row-id');
        $('#rel_qty_'+rowId).css("border-color", "black");
        var tquantity = $('#rem_qty_'+rowId).val();
        var rquantity = $('#rel_qty_'+rowId).val();
        var unitprice = $('#unit_price_'+rowId).val();
        if(parseInt(rquantity) > 0 && parseInt(rquantity) <= parseInt(tquantity)){
            var total = unitprice * rquantity;
            $('#total_amount_'+rowId).val(total.toFixed(2));
            $('.cal_sum').each(function () {
                var totalvalue = $(this).val();
                if(totalvalue != '' && totalvalue != '0')
                {
                    calculated_amount = parseFloat(calculated_amount) + parseFloat(totalvalue);
                    $('#total_of_product').val(calculated_amount);
                }

            });
        }else{
            $('#rel_qty_'+rowId).val('');
            $('#total_amount_'+rowId).val('');
            $('.cal_sum').each(function () {
                var totalvalue = $(this).val();
                if(totalvalue != '' && totalvalue != '0')
                {
                    calculated_amount = parseFloat(calculated_amount) + parseFloat(totalvalue);
                    $('#total_of_product').val(calculated_amount);
                }

            });
            // $('#rel_qty_'+rowId).css("border-color", "red");
            // alert('Receive quantity value must be greater than zero less then or equal to total quantity!');
            // return false;
        }

    })
});