/**
 * Created by abubakar.siddiq on 03/08/2018.
 */

//== Class definition
var ReportFormControls = function () {
    //== Private functions
    var token = $("input[name=_token]").val();
    var baseFunction = function () {
        $('#db-pipeline').hide();
        $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
        $('#select-to option').prop('selected', true);
        $('.select2').select2();

        $('#btn-add').click(function(){
            $('#select-from option:selected').each( function() {
                $('#select-to').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
                $('#select-to option').prop('selected', true);
                $('#select-from option').prop('selected', false);
                $(this).remove();
            });
        });
        $('#btn-remove').click(function(){
            $('#select-to option:selected').each( function() {
                if($(this).val() == 'prospect_name'){
                    alert('Prospect cannot be removed');
                    return false;
                }
                $('#select-from').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
                $('#select-to option').prop('selected', true);
                $('#select-from option').prop('selected', false);
                $(this).remove();
            });
        });
        $('#btn-up').bind('click', function() {
            $('#select-to option:selected').each( function() {
                $('#select-to option').prop('selected', true);
                $('#select-from option').prop('selected', false);
                var newPos = $('#select-to option').index(this) - 1;
                if (newPos > -1) {
                    $('#select-to option').eq(newPos).before("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
                    $(this).remove();
                }
            });
        });
        $('#btn-down').bind('click', function() {
            var countOptions = $('#select-to option').length;
            $('#select-to option').prop('selected', true);
            $('#select-from option').prop('selected', false);
            $('#select-to option:selected').each( function() {
                var newPos = $('#select-to option').index(this) + 1;
                if (newPos < countOptions) {
                    $('#select-to option').eq(newPos).after("<option value='"+$(this).val()+"' selected='selected'>"+$(this).text()+"</option>");
                    $(this).remove();
                }
            });
        });



    }

    var fetchPipelines = function () {


        var datefrom = $('#datefrom').val();
        var dateto = $('#dateto').val();
        var branch_id = $('#branch').val();
        var ppm = $('#ppm').val();
        var product = $('#product').val();
        console.log('datefrom :',datefrom)
        if( ! datefrom || ! branch_id){
            alert('branch and from date are required');
            return false;
        }
        $('#db-pipeline').show();
        $('#db-pipeline-table').DataTable().destroy();
        $('#db-pipeline-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url :'/marketing/salepipelines/govtProspect',
                method: 'POST',
                data:  {
                    name:'temp',
                    branch_id : branch_id,
                    datefrom : datefrom,
                    dateto : dateto,
                    ppm : ppm,
                    product : product,

                    _token: token
                }
            },
            columns: [
                { data: 'bill_no', name: 'bill_no' },
                { data: 'name', name: 'name' },
                { data: 'databank_id', name: 'databank_id' },

                { data: 'emp_name', name: 'emp_name' },

                { data: 'stage', name: 'stage' },
                { data: 'tender_date', name: 'tender_date' },
                { data: 'created_date', name: 'created_date' },
                { data: 'action', name: 'action' },



            ]
        });

    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        fetchPipelines : fetchPipelines
    };

}();

jQuery(document).ready(function() {
    // FormControls.init();
    // 
    $('#excel').on('click', function(e) {
        console.log('hy');
        $( "#form-report" ).submit();
        
    });
   

});
jQuery(document).ready(function() {
    // FormControls.init();
    // 
    $('#pdf').on('click', function(e) {
        console.log('hy');
        $( "#form-pdf" ).submit();
        
    });
   

});

jQuery(document).ready(function() {
    ReportFormControls.init();
});