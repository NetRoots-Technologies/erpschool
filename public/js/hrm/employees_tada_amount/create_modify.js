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


    var getEmployeeDetail = function (val) {
        var user_id = $('#user_id').val();

        var token = $("input[name=_token]").val();
        if(user_id ){

            $.ajax({
                type: "POST",
                url: '/hrm/employees_tada_amount/getEmployeeDetail',
                data: {
                    'user_id' : val,
                    '_token':token,

                },
                success: function(result){
                    result = JSON.parse(result);

                    if(result.job_title){
                        console.log(result);
                        $('#tada_amount').val(result.tada_amount);

                        $('#job_title_id').val(result.job_title_id);
                        $('#job_title').val(result.job_title);

                    }else{
                        alert("No TA/DA defined");
                        $('#tada_amount').val("");

                        $('#job_title_id').val("");
                        $('#job_title').val("");
                    }


                }
            });

        }else{
            alert('Please select required fields');

        }

    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        getEmployeeDetail :getEmployeeDetail,

    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});