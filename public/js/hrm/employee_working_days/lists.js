var token = $("input[name=_token]").val();
        $(function() {
            $('#favoritesModal').on("show.bs.modal", function (e) {
                $("#favoritesModalLabel").html($(e.relatedTarget).data('title'));
                $("#fav-title").html($(e.relatedTarget).data('title'));
                $("#user_id").val($(e.relatedTarget).data('id'));
                $("#date").val($(e.relatedTarget).data('date'));

                if($(e.relatedTarget).data('mode') == 'view'){
                    $('#in-out-datables').show();
                    $('#in-out-datables').addClass('datatable');
                    $('#update').hide();
                    $('#edit-panel').hide();
                    $('#in-out-datables').DataTable().destroy();
                    $('#in-out-datables').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url :'employee_working_days/employee_in_outs',
                            method: 'POST',
                            data:  {
                                'id' : $(e.relatedTarget).data('id'),
                                'date' : $(e.relatedTarget).data('date'),
                                _token : token
                            }
                        },
                        columns: [
                                    { data: 'date_time', name: 'date_time' },
                                    { data: 'attendance_type', name: 'attendance_type' },
                                ],        
                    });
                }
                else{
                    $('#in-out-datables').DataTable().destroy();
                    $('#in-out-datables').hide();
                    $('#in-out-datables').removeClass('datatable');
                    $('#edit-panel').show();
                    $('#update').show();
                }
            });
        });

        $(function(){
            $('#update').on('click', function(){
                if($('#attendance_type').val() == ''){
                    alert('Please Choose a Type');
                }
                else{
                    $.ajax({
                        url: 'employee_working_days/change_attendance_type',
                        type: "POST",
                        data: {
                            'user_id' : $("#user_id").val(),
                            'date' : $("#date").val(),
                            'type_id' : $('#attendance_type').val(),
                            _token : token
                        }, 
                        success:function(data) {
                            location.reload();
                        }
                    });
                }
            });
        });