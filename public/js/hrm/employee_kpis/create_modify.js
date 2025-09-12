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
                name: {
                    required: true,
                  
                },
                year: {
                    required: true
                },
                value: {
                    required: true
                },
                user_id: {
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
    var fetchYTD =  function () {
        var user_id = $('#user_id').val();
        if(! user_id){
            alert('Please choose a user');
            return false;
        }
        if(user_id){
            $.ajax({
                type: "POST",
                url: 'ytdValue',
                data: {
                    'user_id' : user_id,
                    '_token':token,

                },
                success: function(result){
                    var tableHtml = '';
                    console.log('result : ', result);

                    var ytd_array = result.ytd_array;
                    console.log('ytd_array : ', ytd_array);
                    if(ytd_array.length > 0){
                        $.each(ytd_array, function( key, val ) {
                            tableHtml += '<tr>';
                            tableHtml += '<td>'+ (key+1) +'</td>';
                            tableHtml += '<td>'+ val.emp_name+'</td>';
                            tableHtml += '<td>'+ val.target_name +'</td>';
                            tableHtml += '<td>'+ val.value+'</td>';
                            tableHtml += '<td>'+ val.ytd_value +'</td>';
                            tableHtml += '<td>'+ val.ytd_acheived +'</td>';
                            tableHtml += '<td>'+ val.acheived_percent +'</td>';
                            tableHtml += '<td>'+ val.weight +'</td>';
                            tableHtml += '<td>'+ val.weight_percent +'</td>';
                            // tableHtml += '<td>'+ key +'</td>';
                            tableHtml += '</tr>';
                        });

                        tableHtml += '<tr><td colspan="7"></td> <td><b>Total %</b></td>';
                        tableHtml += ' <td><b>'+ result.total_weight_percent+'</b></td></tr>';

                    }else{
                        tableHtml = '<tr><td colspan="4"> No data available </td></tr>'
                    }
                    $('#ytd-body').html(tableHtml);
                }

            });
        }

    }
    return {
        // public functions
        init: function() {
            baseFunction();
        },
        fetchYTD : fetchYTD
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});