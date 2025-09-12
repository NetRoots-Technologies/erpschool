
var FormControls = function () {
    var token = $("input[name=_token]").val();
    $('#user_id').on('change',function(){
        if($('#user_id').val()){
            fetchFilterRecord();
        }
        else{
            alert('Please Select an Employee');
        }
    });
    $('.select2').select2();
    $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                 url :'employee_documents/datatables',
                    method: 'POST',
                    data:  {
                        user_id : 'abcd',
                        file_location : 'abcd',
                        document_type : 'abcd',
                        _token: token,
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'document_type', name: 'document_type' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' , orderable: false, searchable: false},

                ]
        });


    var baseFunction = function () {



    }

    var fetchFilterRecord = function () {
        var token = $("input[name=_token]").val();
        var user_id = $('#user_id').val();
        $('#users-table').DataTable().destroy();
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url :'employee_documents/datatables',
                method: 'POST',
                data:  {
                    user_id : user_id,
                    file_location : 'abcd',
                    document_type : 'abcd',
                    _token: token
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'document_type', name: 'document_type' },
                { data: 'user_name', name: 'user_name' },
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