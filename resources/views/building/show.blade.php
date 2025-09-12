@extends('admin.layouts.main')

@section('title')
    {{ __('Building Details') }}
@stop

@section('content')

<div class="row">
    <div class="col-md-12">
            <div class="card-body text-end">
                <a href="#" id="openFloorModal" class="btn btn-primary">
            <i class="ti-plus mr-1"></i> {{ __('Add Floor') }}
        </a>

                <a href="" 
                   class="btn btn-success customModal" 
                   data-title="{{ __('Add Unit') }}" 
                   data-size="lg" 
                   data-url="">
                   <i class="ti-plus mr-1"></i> {{ __('Add Unit') }}
                </a>
            </div>
        
    </div>
</div>


<div class="row">
    <div class="col-md-5">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                {{ __('Building Image') }}
            </div>
            <div class="card-body text-center">
                @if(!empty($buildings->image))
                    <img src="{{ asset($buildings->image) }}" class="img-fluid rounded shadow-sm" alt="Building Image">
                @else
                    <img src="{{ asset('upload/thumbnail/default.jpg') }}" class="img-fluid rounded shadow-sm" alt="Default Image">
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        
    </div>

    <!-- Right side: Details -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-primary text-white">
                {{ $buildings->name }}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ __('Building Information') }}</h5>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item">
                        <strong>{{ __('Name:') }}</strong> {{ $buildings->name }}
                    </li>
                    <li class="list-group-item">
                        <strong>{{ __('Area (sqm):') }}</strong> {{ $buildings->area ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <strong>{{ __('Company:') }}</strong> {{ $buildings->company->name ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <strong>{{ __('Branch:') }}</strong> {{ $buildings->branch->name ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <strong>{{ __('Created At:') }}</strong> {{ $buildings->created_at->format('d-m-Y') }}
                    </li>
                </ul>

                <h5 class="mt-3">{{ __('Description') }}</h5>
                <p>{{ $buildings->description ?? __('No description available') }}</p>
            </div>
        </div>
    </div>


    {{-- Units --}}

    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                {{ $buildings->name }}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ __('Building Information') }}</h5>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item">
                        <strong>{{ __('Name:') }}</strong> {{ $buildings->name }}
                    </li>
                    <li class="list-group-item">
                        <strong>{{ __('Area (sqm):') }}</strong> {{ $buildings->area ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <strong>{{ __('Company:') }}</strong> {{ $buildings->company->name ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <strong>{{ __('Branch:') }}</strong> {{ $buildings->branch->name ?? '-' }}
                    </li>
                    <li class="list-group-item">
                        <strong>{{ __('Created At:') }}</strong> {{ $buildings->created_at->format('d-m-Y') }}
                    </li>
                </ul>

                <h5 class="mt-3">{{ __('Description') }}</h5>
                <p>{{ $buildings->description ?? __('No description available') }}</p>
            </div>
        </div>
    </div>
</div>


  


        {{-- Floor Modal --}}
        <div class="modal fade" id="FloorcustomModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document"> <!-- small modal -->
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add Floor') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Form Inside Modal --}}
            {!! Form::open(['route' => ['building.floor.store', $buildings->id], 'method' => 'POST', 'id' => 'ajaxFloorForm']) !!}
            <div class="modal-body">
                <input type="hidden" name="building_id" value="{{ $buildings->id }}" id="building_id">

                <div class="form-group">
                    {{ Form::label('name', __('Floor Name')) }}
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter floor name'), 'required' => true]) }}
                </div>

                <div class="form-group">
                    {{ Form::label('area', __('Area (sqm)')) }}
                    {{ Form::number('area', null, ['class' => 'form-control', 'placeholder' => __('Enter floor area'), 'step' => '0.1', 'required' => true]) }}
                </div>

                <div class="form-group">
                    {{ Form::label('floor_type_id', __('Floor Type')) }}
                    {{ Form::select('floor_type_id', $floor_type, null, ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select Floor Type')]) }}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-info closeModalBtn" data-dismiss="modal">{{ __('Close') }}</button>
                {{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
            </div>
            {!! Form::close() !!}
            </div>
        </div>
        </div>

{{-- Floor Modal Create End --}}
@endsection

@section('js')
<script>

$(document).ready(function () {
       
   

       $('#openFloorModal').on('click', function(e){
        e.preventDefault();
        $('#FloorcustomModal').modal('show');
    });

     $('.closeModalBtn').on('click', function(e){
        e.preventDefault();
        $('#FloorcustomModal').modal('hide');
    });

    

    // AJAX Submit

                    //  $('#ajaxFloorForm').on('click', function (e) {
                    //     e.preventDefault();
                    //     var id = $('#edit_id').val();

                    //     $.ajax({
                    //         type: "POST",
                    //         url: "{{ route('type.update', ':id') }}".replace(':id', id),
                    //         data: $('#editform').serialize(),
                    //         success: function (response) {
                    //             $('#editTypeModal').modal('hide');
                    //             tableData.ajax.reload(null, false);
                    //             toastr.success('Type updated successfully.');
                    //         },
                    //         error: function () {
                    //             toastr.error('Type not updated.');
                    //         }
                    //     });
                    // });

        $('#ajaxFloorForm').on('submit', function(e){
            e.preventDefault();
            var form = $(this);
            var formData = form.serialize();

            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: formData,
                success: function(res){

                        toastr.success('Type updated successfully.');
                        $('#FloorcustomModal').modal('hide');
                        location.reload(); // refresh list
                    
                },
                error: function(xhr){
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            });
        });
 
    });

</script>
@endsection

