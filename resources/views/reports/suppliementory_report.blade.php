@extends('admin.layouts.main')

@section('title') variance Report @stop

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">Variance Report</div>
        <div class="card-body">
            {{-- <div class="row">

             
                <div class="col-md-3">
                    <label>Budget</label>
                    <select id="budget_id">
                        <option value="">All Budgets</option>
                        @foreach ($budgets as $budget)
                            <option value="{{ $budget->id }}">{{ $budget->title }}</option>
                        @endforeach
                    </select>
                </div>


                
                <div class="col-md-3">
                    <label>Category</label>
                    <select id="category_id">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                        @endforeach
                    </select>
                </div>

            
                <div class="col-md-3">
                    <label>Sub Category</label>
                    <select id="sub_category_id">
                        <option value="">All Sub Categories</option>
                        @foreach ($subCategories as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}

            <div class="mt-4">
                <table id="variance-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Allocated Budget</th>
                            <th>Supplementary Budget</th>
                            {{-- <th>Total Allowed</th> --}}
                            <th>Actual Expense</th>
                            <th>Variance</th>
                        </tr>
                    </thead>
                </table>



            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function() {

            let table = $('#variance-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('inventory.supplimentory.budget.report') }}",
                    data: function(d) {
                        d.category_id = $('#category_id').val();
                        d.sub_category_id = $('#sub_category_id').val();
                    }
                },
                columns: [{
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'allocated_budget',
                        name: 'allocated_budget'
                    },
                    {
                        data: 'supplementary_budget',
                        name: 'supplementary_budget'
                    },
                    // {
                    //     data: 'total_allowed',
                    //     name: 'total_allowed'
                    // },
                    {
                        data: 'actual_expense',
                        name: 'actual_expense'
                    },
                    {
                        data: 'variance',
                        name: 'variance'
                    },
                ]
            });

            $('#category_id, #sub_category_id').change(function() {
                table.ajax.reload();
            });


        });

        // Student report view
        // $(document).on('click', '.view-student', function() {
        //     let studentId = $(this).data('id');
        //     let url = "{{ route('reports.exam.view', ':id') }}".replace(':id', studentId);
        //     window.location.href = url;
        // });
    </script>
@endsection
