$(document).ready(function () {
    var bulkActionUrl = '';

    var tableData = $('#data_table').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 20,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'collection',
                className: "btn-light",
                text: 'Export',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            },
            {
                extend: 'collection',

                text: 'Bulk Action',
                className: 'btn btn-light',
                buttons: [
                    {
                        text: '<i class="fas fa-trash"></i> Delete',
                        className: 'btn btn-danger delete-button',
                        action: function () {
                            var selectedIds = [];

                            $('#data_table').find('.select-checkbox:checked').each(function () {
                                selectedIds.push($(this).val());
                            });

                            if (selectedIds.length > 0) {
                                $('.dt-button-collection').hide();

                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: 'You are about to perform a bulk action!',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, delete it!',
                                    cancelButtonText: 'Cancel'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: bulkActionUrl,
                                            type: 'POST',
                                            data: {
                                                ids: selectedIds,
                                                "_token": "{{ csrf_token() }}",
                                            },
                                            dataType: 'json',
                                            success: function (response) {
                                                tableData.ajax.reload();
                                                Swal.fire('Deleted!', 'Your data has been deleted.', 'success');
                                            },
                                            error: function (xhr, status, error) {
                                                console.error(xhr.responseText);
                                                alert('AJAX request failed: ' + error);
                                            }
                                        });
                                    }
                                });
                            } else {
                                alert('No checkboxes selected.');
                            }
                        }
                    },
                ],
            },

            {
                extend: 'colvis',
                columns: ':not(:first-child)'
            }
        ],
        columnDefs: [
            {visible: false}
        ]
    });

    // Function to check/uncheck all checkboxes
    function checkAll(source) {
        var checkboxes = document.querySelectorAll('.select-checkbox');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    });

