/**
 * Created by mustafa.mughal on 30/01/2018.
 */

//== Class definition
var FormControls = function () {
    //== Private functions
    var token = $("input[name=_token]").val();

    $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
    $('.select2').select2();

    var baseFunction = function () {

    }

    var submitForm =  function () {
        var brand = $('#brand').val();
        var category = $('#category').val();
        var product_id = $('#product_id').val();
        var class_type = $('#class_type').val();
        var product_type = $('#product_type').val();
        var type = $('#type').val();
        var year = $('#year').val();
        if(!type ){
            alert('Please choose target type ')
            return false;
        }
         if( !year){
            alert('Please choose target year ')
             return false;
        }

        if(!brand && !category && !product_id && !class_type && !product_type){
            alert('Please Choose atleast one option');
            return false;
        }else{

            $('#validation-form').submit();
        }

    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        submitForm: submitForm
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});