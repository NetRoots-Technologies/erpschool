@extends('admin.layouts.main')


@section('content')
    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8"></div>
                        @if (Gate::allows('students'))

                        <div class="col-md-4">
                            <a href="{{route('admin.items.create')}}" class="btn btn-success mb-3 float-end"
                               type="submit">Add Item</a>
                        </div>
                        @endif
                    </div>

                    <table class="table table-bordered data-table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th width="100px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function () {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('items.getData') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'category_id', name: 'category_id'},
                    {data: 'sub_category_id', name: 'sub_category_id'},
                    {data: 'code', name: 'Code'},
                    {data: 'name', name: 'Name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });
    </script>
@endsection
