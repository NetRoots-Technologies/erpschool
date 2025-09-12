
var FormControls = function () {
    var token = $("input[name=_token]").val();

    $(".datepicker").datepicker({ format: 'yyyy-mm-dd' });
    $('.select2').select2();
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                 url :'employee_resources/datatables',
                    method: 'POST',
                    data:  {
                        user_id : 'abcd',
                        _token: token,
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'product', name: 'product' },
                    { data: 'grant_date', name: 'grant_date' },
                    { data: 'return_date', name: 'return_date' },
                    { data: 'worth', name: 'worth' },

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
                url :'employee_resources/datatables',
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
                { data: 'product', name: 'product' },
                { data: 'grant_date', name: 'grant_date' },
                { data: 'return_date', name: 'return_date' },
                { data: 'worth', name: 'worth' },

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