/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        $('#date_of_joining').datetimepicker({
        format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
        sideBySide: true
        });
        $('#contract_start_date').datetimepicker({
            format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
            sideBySide: true
        });
        $('#contract_end_date').datetimepicker({
            format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
            sideBySide: true
        });
        $('#pre_job_date_from').datetimepicker({
            format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
            sideBySide: true
        });
        $('#pre_job_date_to').datetimepicker({
            format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
            sideBySide: true
        });
        $('#edu_start_date').datetimepicker({
            format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
            sideBySide: true
        });
        $('#edu_end_date').datetimepicker({
            format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
            sideBySide: true
        });
        $('#date_of_resign').datetimepicker({
            format: 'YYYY-MM-DD', //format: 'DD-MM-YYYY H:m:s A',
            sideBySide: true
        });
        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {

                department_id: {
                    required: true

                }, job_title: {
                    required: true

                }, region_id: {
                    required: true

                }, branch_id: {
                    required: true

                }, territory_id: {
                    required: true

                }, job_category: {
                    required: true

                }, employee_status: {
                    required: true

                }, date_of_joining: {
                    required: true

                }, probation_period: {
                    required: true

                }, contract_start_date: {
                    required: true

                }, contract_end_date: {
                    required: true

                }, report_to: {
                    required: true
                }, bank_name: {
                    required: true

                }, account_number: {
                    required: true

                }, basic_salary: {
                    required: true

                }, pre_job_title: {
                    required: true

                }, pre_company: {
                    required: true

                }, pre_job_date_from: {
                    required: true

                }, pre_job_date_to: {
                    required: true

                }, skills_experiance: {
                    required: true

                },skill_title: {
                    required: true

                },edu_institute: {
                    required: true

                },edu_specialization: {
                    required: true

                },edu_year: {
                    required: true

                },edu_score: {
                    required: true

                },edu_start_date: {
                    required: true

                },edu_end_date: {
                    required: true

                },date_of_resign: {
                    required: true

                },termination_reason: {
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
    $('#department_id').change(function ()
    {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route('admin.employees.get_job_title_by_departmen_id_ajax'),
            type: "POST",
            data: {


                department_id: $('#department_id').val()

            },
            success: function(response){

                var JobtitleData = response.jobtitledata;
                var newHTml = '<option value="">Select Job Title</option>'
                for( var i=0 ; i < JobtitleData.length;  i++){
                    //console.log(JobtitleData[i].id);
                    newHTml += '<option value=' + JobtitleData[i].id + '>' + JobtitleData[i].name + '</option>';
                }
                $('#job_title')
                    .empty()
                    .append(newHTml)
                ;
            },
            error: function (xhr, ajaxOptions, thrownError) {

                return false;
            }
        });

    });
    $('#region_id').change(function ()
    {

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



    if($('#job_title option:selected').val() ==1 || $('#job_title option:selected').val() == 2 )
    {

        $('#region_id_h').hide();
        $('#branch_id_h').hide();
        $('#territory_id_h').hide();
    }
   else if($('#job_title option:selected').val() ==8 || $('#job_title option:selected').val() == 9 || $('#job_title option:selected').val() == 10)
    {
        $('#region_id_h').hide();
        $('#branch_id_h').hide();
        $('#territory_id_h').hide();
    }
   else if($('#job_title option:selected').val() ==16 || $('#job_title option:selected').val() == 17 || $('#job_title option:selected').val() == 18)
    {
        $('#region_id_h').hide();
        $('#branch_id_h').hide();
        $('#territory_id_h').hide();
    }
   else if($('#job_title option:selected').val() == 21 )
    {
        $('#region_id_h').hide();
        $('#branch_id_h').hide();
        $('#territory_id_h').hide();
    }else if($('#job_title option:selected').val() == 22 )
    {
        $('#region_id_h').show();
        $('#branch_id_h').hide();
        $('#territory_id_h').hide();
    }else if($('#job_title option:selected').val() == 23 )
    {
        $('#region_id_h').show();
        $('#branch_id_h').show();
        $('#territory_id_h').hide();
    }else if($('#job_title option:selected').val() == 24 )
    {
        $('#region_id_h').show();
        $('#branch_id_h').show();
        $('#territory_id_h').show();
    }else{
        $('#region_id_h').show();
        $('#branch_id_h').show();
        $('#territory_id_h').show();
    }
    if($('#department_id option:selected').val() != '' )
    {
        var title = $('#job_title option:selected').val();
        var selected = '';
        var department_id  = $('#department_id option:selected').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route('admin.employees.get_job_title_by_departmen_id_ajax'),
            type: "POST",
            data: {


                department_id: department_id

            },
            success: function(response){

                var JobtitleData = response.jobtitledata;

                var newHTml = '<option value="">Select Job Title</option>'
                for( var i=0 ; i < JobtitleData.length;  i++){
                    //console.log(JobtitleData[i].id);
                    if(JobtitleData[i].id == title )
                    {
                        selected = 'Selected';
                    }
                    newHTml += '<option value=' + JobtitleData[i].id  +'>' + JobtitleData[i].name + '</option>';
                }
                $('#job_title')
                    .empty()
                    .append(newHTml)
                ;
                $('#job_title').val(title);
            },
            error: function (xhr, ajaxOptions, thrownError) {

                return false;
            }
        });

    }

    $('#job_title').change(function (){

        if($('#department_id option:selected').val() == 1  )
        {
            if($('#job_title option:selected').val() ==1 || $('#job_title option:selected').val() == 2 )
            {
                $('#region_id_h').hide();
                $('#branch_id_h').hide();
                $('#territory_id_h').hide();
            }else{
                $('#region_id_h').show( "slow" );
                $('#branch_id_h').show( "slow" );
                $('#territory_id_h').show( "slow" );
            }
        }
        if($('#department_id option:selected').val() == 2  )
        {
            if($('#job_title option:selected').val() ==8 || $('#job_title option:selected').val() == 9 || $('#job_title option:selected').val() == 10)
            {
                $('#region_id_h').hide();
                $('#branch_id_h').hide();
                $('#territory_id_h').hide();
            }else{
                $('#region_id_h').show( "slow" );
                $('#branch_id_h').show( "slow" );
                $('#territory_id_h').show( "slow" );
            }
        }
        if($('#department_id option:selected').val() == 3  )
        {
            if($('#job_title option:selected').val() ==16 || $('#job_title option:selected').val() == 17 || $('#job_title option:selected').val() == 18)
            {
                $('#region_id_h').hide();
                $('#branch_id_h').hide();
                $('#territory_id_h').hide();
            }else{
                $('#region_id_h').show( "slow" );
                $('#branch_id_h').show( "slow" );
                $('#territory_id_h').show( "slow" );
            }
        }
        if($('#department_id option:selected').val() == 4  )
        {
            if($('#job_title option:selected').val() == 21 )
            {
                $('#region_id_h').hide();
                $('#branch_id_h').hide();
                $('#territory_id_h').hide();
            }else if($('#job_title option:selected').val() == 22 )
            {
                $('#region_id_h').show( "slow" );
                $('#branch_id_h').hide();
                $('#territory_id_h').hide();
            }else if($('#job_title option:selected').val() == 23 )
            {
                $('#region_id_h').show( "slow" );
                $('#branch_id_h').show( "slow" );
                $('#territory_id_h').hide();
            }else if($('#job_title option:selected').val() == 24 )
            {
                $('#region_id_h').show( "slow" );
                $('#branch_id_h').show( "slow" );
                $('#territory_id_h').show( "slow" );
            }else{
                $('#region_id_h').show( "slow" );
                $('#branch_id_h').show( "slow" );
                $('#territory_id_h').show( "slow" );
            }
        }

    });
     $('#branch_id').change(function ()
    {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route('admin.employees.get_territory_ajax'),
            type: "POST",
            data: {


                branch_id: $('#branch_id').val()

            },
            success: function(response){

                var TempterritoryData = response.territorydata;
                var newHTml = '<option value="">Select Territory</option>'
                for( var i=0 ; i < TempterritoryData.length;  i++){
                    //console.log(TempterritoryData[i].id);
                    newHTml += '<option value=' + TempterritoryData[i].id + '>' + TempterritoryData[i].name + '</option>';
                }
                $('#territory_id')
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