@extends('admin.layouts.main')

@section('title')
    {{__('Building Create')}}
@endsection

@section('content')
    {!! Form::open(['route' => 'building.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-primary">Create Building</div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
                                {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Building Name')))}}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {{Form::label('area',__('Area(sqm)'),array('class'=>'form-label'))}}
                                {{Form::number('area',null,array('class'=>'form-control','placeholder'=>__('Enter Building Area') ,'step'=>'0.1'))}}
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('company_id', __('Company Name'), ['class' => 'form-label']) }}
                            {{ Form::select('company_id', $companies, null, ['class' => 'form-control select2', 'id' => 'company_id', 'placeholder' => __('Select Company')]) }}
                        </div>
                    </div>


                        <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('branch_id', __('Branch Name'), ['class' => 'form-label']) }}
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="">{{ __('Select Branch') }}</option>
                            </select>
                        </div>
                        </div>

                    </div>

                    <div class="row">

                         <div class="col-lg-12">
                        
                        <div class="form-group">
                            {{Form::label('description',__('Description'),array('class'=>'form-label'))}}
                            {{Form::textarea('description',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Enter Building Description')))}}
                        </div>

                        </div>

                         <div class="col-lg-12">

                            <div class="form-group">
                                {{Form::label('thumbnail',__('Thumbnail Image'),array('class'=>'form-label'))}}
                                {{Form::file('thumbnail',array('class'=>'form-control'))}}
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="group-button text-end">
                {{Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded','id'=>'property-submit'))}}
            </div>
        </div>
    </div>
    {{ Form::close() }}
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

