
var FormControls = function () {
    var token = $("input[name=_token]").val();

    $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
    $('.select2').select2();
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                 url :'employees_overtime/datatables',
                    method: 'POST',
                    data:  {
                        user_id : 'abcd',
                        _token: token,
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'allowed_hours', name: 'allowed_hours' },
                    { data: 'for_date', name: 'for_date' },
                    { data: 'allowed_by', name: 'allowed_by' },
                    { data: 'approved_by', name: 'approved_by' },
                    { data: 'work_status', name: 'work_status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' , orderable: false, searchable: false},

                ]
        });


    var baseFunction = function () {



    }




    var fetchFilterRecord = function () {

        var user_id = $('#user_id').val();

        $('#users-table').DataTable().destroy();
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url :'employees_overtime/datatables',
                method: 'POST',
                data:  {
                    user : user_id,
                    user_id : 'temp',
                    _token: token
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'user_name', name: 'user_name' },
                { data: 'allowed_hours', name: 'allowed_hours' },
                { data: 'allowed_by', name: 'allowed_by' },
                { data: 'approved_by', name: 'approved_by' },
                { data: 'for_date', name: 'for_date' },
                { data: 'work_status', name: 'work_status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action' , orderable: false, searchable: false},

            ]
        });

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
});