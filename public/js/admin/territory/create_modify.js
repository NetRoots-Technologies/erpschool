/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

        $('#branch_id').select2();
    var baseFunction = function () {

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                region_id: {
                    required: true
                },
                name: {
                    required: true
                },
                city_id: {
                    required: true
                },
                region_name: {
                    required: true
                },
                country_id: {
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
$(document).ready(function() {

    $('#region_id').change(function ()
    {
        console.log(route('admin.employees.get_branches_ajax'));

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route('admin.employees.get_branches_ajax'),
            type: "POST",
            data: {

                region_id: $('#region_id').val()


            },
            success: function(response){
                console.log(response.branchesdata);
                var TempbranchData = response.branchesdata;
                var newHTml = '<option value="">Select Branch</option>'
                for( var i=0 ; i < TempbranchData.length;  i++){
                    //console.log(TempbranchData[i].id);
                    newHTml += '<option value=' + TempbranchData[i].id + '>' + TempbranchData[i].name + '</option>';
                }
                $('#branch_id')
                    .empty()
                    .append(newHTml)
                ;
            },
            error: function (xhr, ajaxOptions, thrownError) {

                return false;
            }
        });
    });
});