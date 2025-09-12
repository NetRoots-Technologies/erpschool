
var FormControls = function () {
    var token = $("input[name=_token]").val();
    $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
    $('.select2').select2();
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                 url :'payroll/datatables',
                    method: 'POST',
                    data:  {
                        name : 'abcd',
                        _token: token
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'month', name: 'month' },
                    { data: 'year', name: 'year' },
                    { data: 'gross_total', name: 'gross_total' },
                    { data: 'paid_date', name: 'paid_date' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' , orderable: false, searchable: false},

                ]
        });


    var baseFunction = function () {



    }

    var fetchFilterRecord = function () {
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var user_id = $('#user_id').val();

        if(from_date != '' && to_date != '' && user_id == ''){
            alert('User field is required');
        }
        else{
            $('#users-table').DataTable().destroy();
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url :'payroll/datatables',
                    method: 'POST',
                    data:  {
                        name : 'temp',
                        from_date : from_date,
                        to_date : to_date,
                        user_id : user_id,
                        _token: token
                    }
                },
                columns: [
                    
                        { data: 'id', name: 'id' },
                        { data: 'user_name', name: 'user_name' },
                        { data: 'month', name: 'month' },
                        { data: 'year', name: 'year' },
                        { data: 'gross_total', name: 'gross_total' },
                        { data: 'paid_date', name: 'paid_date' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action', name: 'action' , orderable: false, searchable: false},
    
                        ]
            });
        }

    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },

        fetchFilterRecord : fetchFilterRecord,

    };
}();

jQuery(document).ready(function() {
    FormControls.init();
    $("#myBtn").click(function(){
        $("#myModal").modal();
    });
});