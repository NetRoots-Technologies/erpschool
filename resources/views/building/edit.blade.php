@extends('admin.layouts.main')

@section('title')
    {{ __('Building Edit') }}
@endsection

@section('content')
    {!! Form::model($buildings, [
        'route' => ['building.update', $buildings->id],
        'method' => 'POST',
        'enctype' => 'multipart/form-data'
    ]) !!}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-primary">Edit Building</div>
                <div class="card-body">
                    <div class="row">
                        {{-- Name --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                                {{ Form::text('name', $buildings->name, ['class' => 'form-control', 'placeholder' => __('Enter Building Name')]) }}
                            </div>
                        </div>

                        {{-- Area --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('area', __('Area(sqm)'), ['class' => 'form-label']) }}
                                {{ Form::number('area', $buildings->area, ['class' => 'form-control', 'placeholder' => __('Enter Building Area'), 'step' => '0.1']) }}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Company --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('company_id', __('Company Name'), ['class' => 'form-label']) }}
                                {{ Form::select('company_id', $companies, $buildings->company_id, ['class' => 'form-control select2', 'id' => 'company_id', 'placeholder' => __('Select Company')]) }}
                            </div>
                        </div>

                        {{-- Branch --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('branch_id', __('Branch Name'), ['class' => 'form-label']) }}
                                <select name="branch_id" id="branch_id" class="form-control">
                                    <option value="">{{ __('Select Branch') }}</option>
                                    @if($buildings->branch_id)
                                        <option value="{{ $buildings->branch_id }}" selected>
                                            {{ $buildings->branch->name ?? '-' }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Description --}}
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Building Description')]) }}
                            </div>
                        </div>

                        {{-- Thumbnail --}}
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::label('thumbnail', __('Thumbnail Image'), ['class' => 'form-label']) }}
                                {{ Form::file('thumbnail', ['class' => 'form-control']) }}
                                @if(!empty($buildings->image))
                                    <img src="{{ asset($buildings->image) }}" alt="Thumbnail" class="mt-2" style="width: 120px; height: auto;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="col-lg-12 mt-3">
            <div class="group-button text-end">
                {{ Form::submit(__('Update'), ['class' => 'btn btn-primary btn-rounded']) }}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('js')
<script>
    $(document).ready(function () {
        $('#company_id').on('change', function () {
            var companyId = $(this).val();
            if (companyId) {
                $.ajax({
                    url: "{{ url('get-branch-by-company') }}/" + companyId,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#branch_id').empty();
                        $('#branch_id').append('<option value="">{{ __("Select Branch") }}</option>');
                        $.each(data, function (key, value) {
                            $('#branch_id').append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                });
            } else {
                $('#branch_id').empty();
                $('#branch_id').append('<option value="">{{ __("Select Branch") }}</option>');
            }
        });

    });


</script>
@endsection
