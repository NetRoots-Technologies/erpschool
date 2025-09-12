@extends('admin.layouts.main')

@section('title')
Chart Of Accounts
@stop

@section('content')

<div class="row box box-primary" style="background-color: white">
    <div class="container-fluid">


       <div class="row w-100 mt-4">
        <h3 class="text-22 text-center text-bold w-100 mb-4">
            <a href="{{ route('admin.ledger_tree') }}" class="text-decoration-none text-dark">
                Chart Of Accounts
            </a>
        </h3>

      <div class="d-flex mb-3" style="padding-left: 35px;">
        @if (Gate::allows('students'))

            <button class="btn btn-primary" onclick="printTable()">Print</button>
            <a href="{{ route('admin.groups.create') }}" class="btn btn-primary ms-2">Add Chart of Accounts</a>
            @enfif
        </div>

    </div>

    </div>
    <div class="row">
        <div class="col-lg-12 p-5 panel-body pad table-responsive" id="printSection">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="20%"><b>Number</b></th>
                        <th width="5%"><b>Type</b></th>
                        <th width="55%"><b>Name</b></th>
                        <th width="20%"><b>Actions</b></th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($Ledgers) > 0)
                    @foreach ($Ledgers as $id => $data)
                    @if ($id == 0) @continue; @endif
                    <tr>
                        <td>
                            @if($id < 0) <span style="color: maroon;font-weight: bold">{!! $data['number'] !!}</span>
                                @else
                                <span style="color: darkblue;">{!! $data['number'] !!}</span>
                                @endif
                        </td>
                        <td>
                            @if ($id < 0) <span style="color: maroon;font-weight: bold">Group</span>
                                @else <span style="color: darkblue;">Ledger</span>
                                @endif
                        </td>
                        <td>
                            @if ($id < 0) <span style="color: maroon;font-weight: bold">{!! $data['name'] !!}</span>
                                @else
                                <span style="color: darkblue;">
                                    @if($type == 'web')
                                    <a href="{{ route('admin.ledger.edit',[$id]) }}">
                                        <?php echo $data['name'] ?>
                                    </a>
                                    @else
                                    <?php echo $data['name'] ?>
                                    @endif
                                </span>
                                @endif
                        </td>
                        <td>
                            @if ($id > 0 && !in_array($id, Config::get('constants.accounts_main_heads')))
                                <a href="{{ route('admin.groups.edit',[$id]) }}" class="ml-2 btn mb-1 btn-primary">
                                    <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                </a>

                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'onsubmit' => "return confirm('Are you sure you want to delete this?');",
                                    'route' => ['admin.groups.destroy', $id]
                                ]) !!}
                                    {{ csrf_field() }}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger ml-2']) !!}
                                {!! Form::close() !!}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td align="center" colspan="3">No entries in a table</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // function printTable() {
    //     var printContent = document.getElementById("printSection").innerHTML;
    //     var originalContent = document.body.innerHTML;

    //     document.body.innerHTML = printContent;
    //     window.print();
    //     document.body.innerHTML = originalContent;
    //     location.reload();
    // }
</script>

@endsection
