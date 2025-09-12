@extends('admin.layouts.main')

@section('title')
OverTime
@stop


@section('content')
<div class="container-fluid">
    <div class="row w-100 text-center">
        <div class="card p-4 shadow-sm w-100">
            <h5 class="mb-3"><b>Select Month</b></h5>
            <div class="row mt-3">
                <div class="col-4">
                    <label for="month" class="form-label"><b>Month*</b></label>
                    <input type="month" id="month" class="form-control" value="{{$currentMonth}}" required>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center my-4">
        <div class="col-12">
            <div class="card basic-form shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped mb-0" id="data_table">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script defer>
    $(document).ready(function(){
            'use strict';
            const uri = @json(route('hr.overtime-report'))

            const dt = $('#data_table').DataTable({
                ajax: {
                    url: uri,
                    type: 'GET',             
                    dataSrc: function (json) {
                        return json;
                    },
                    data: function (d) {
                        d.month = $('#month').val();
                    },
                },
                columns: [
                    { data: 'id', title: 'Sr No' , width: "7%"},  
                    { data: 'employee.name', title: 'Name' },
                    { data: 'branch.name', title: 'Branch Name' },
                    { data: 'start_date', title: 'Month' },
                    { data: 'total', title: 'Amount' },  
                ],
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                language: {
                    emptyTable: 'No data available in the table.'
                }
            });

            $('#month').on('change', function(e){
                dt.ajax.reload();
            })
        })
</script>
@endsection