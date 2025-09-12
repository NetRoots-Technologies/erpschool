@extends('admin.layouts.main')


@section('content')
    <div class="container">
        <div class="row justify-content-center p-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header"><strong>Update Ledger</strong> <span class="float-end"><a
                                    href="{{ route('admin.ledgers.index') }}" class="btn btn-primary">Back</a></span>
                        </div>
                        <div class="card-body">
                            {!! Form::model($Ledger, ['method' => 'PUT', 'route' => ['admin.ledgers.update', $Ledger->id]]) !!}
                            @csrf
                            <div class="row">
                                @include('admin.ledgers.fields')
                            </div>
                            {!! Form::submit('Save', ['class' => 'btn btn-danger globalSaveBtn']) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

<script type="text/javascript">
    function more_ob() {
        $("#add-more").append('<div class="row">' +
            '<div class="form-group col-md-3">' +
            '<select name="currency_id[]" class="form-control" value="{{ old('currency') }}">' +
            '{!! \App\Helpers\Currency::currencyList() !!}' +
            '</select>' +
            '</div>' +
            '<div class="form-group col-md-3">' +
            '<select name="balance_type[]" class="form-control">' +
            '{!! \App\Helpers\CoreAccounts::dr_cr() !!}' +
            '</select>' +
            '</div>' +
            '<div class="form-group col-md-3">' +
            '<input type="number" name="amount[]" class="form-control" value="{{ old('amount') }}">' +
            '</div>' +
            '</div>');
    }
</script>
@section('javascript')

    <script src="{{ url('/js/admin/ledgers/create_modify.js') }}" type="text/javascript"></script>
@endsection
