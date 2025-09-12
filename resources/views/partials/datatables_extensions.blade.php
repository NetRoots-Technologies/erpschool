<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
<script type='text/javascript'>
    $(document).ready(function() {
        var table = $('#tablewithextensions').DataTable({
            fixedColumns: {
                leftColumns: 1
            },
            'fixedHeader' : true,
            // 'scrollX' : true,
            // 'scrollY' : '400px',
            paging: false,
            searching: false,
            ordering:  false,
            info: false,
            lengthChange: false,
            buttons: ['pageLength',
                {
                    extend: 'excel',
                    text: 'Export excel',
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];

                        // Loop over the cells in column `C`
//                            $('row:first  c', sheet).attr('s', '40');
//                            $('row:nth-child(1)  c', sheet).attr('s', '40');
                        $('row:last  c', sheet).attr('s', '40');
                        $('row c[r^="A2"]', sheet).attr('s', '20');
                        $('row c[r^="B2"]', sheet).attr('s', '20');
                        $('row c[r^="C2"]', sheet).attr('s', '20');
                        $('row c[r^="D2"]', sheet).attr('s', '20');
                        $('row c[r^="E2"]', sheet).attr('s', '20');
                    },
                    exportOptions: {
                        columns: ':visible',
                    }
                }
            ]
        });

        // table.buttons().container()
        table.buttons().container().appendTo('#tablewithextensions_wrapper :eq(0)');
    } );
</script>
