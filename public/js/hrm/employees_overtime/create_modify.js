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
        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                '#user_id': {
                    required: true
                },
                '#job_title': {
                    required: true
                },
                '#allowed_hours': {
                    required: true
                },
                '#for_date': {
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
        });
    }


    var getEmployeeDetail = function (val) {
        var user_id = $('#user_id').val();
        var token = $("input[name=_token]").val();
        if(user_id ){
            $.ajax({
                type: "POST",
                url: '/hrm/employees_overtime/getEmployeeDetail',
                data: {
                    'user_id' : val,
                    '_token':token,

                },
                success: function(result){
                    result = JSON.parse(result);
                    if(result.job_title){
                        console.log(result);
                        $('#job_title_id').val(result.job_title_id);
                        $('#job_title').val(result.job_title);

                    }else{
                        alert("No Employee Found !");
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