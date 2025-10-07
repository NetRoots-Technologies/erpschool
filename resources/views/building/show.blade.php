@extends('admin.layouts.main')

@section('title')
    {{ __('Building Details') }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card-body text-end">
                <a href="#" id="openFloorModal" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Floor') }}
                </a>

                <a href="" class="btn btn-success" id = "unitCustomModal" data-title="{{ __('Add Unit') }}"
                    data-size="lg">
                    <i class="fa fa-plus"></i> {{ __('Add Unit') }}
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
                    @if (!empty($buildings->image))
                        <img src="{{ asset($buildings->image) }}" class="img-fluid rounded shadow-sm" alt="Building Image">
                    @else
                        <img src="{{ asset('upload/thumbnail/default.jpg') }}" class="img-fluid rounded shadow-sm"
                            alt="Default Image">
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
                            <strong>{{ __('Area(sqft):') }}</strong> {{ $buildings->area ?? '-' }}
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


        {{-- Floors --}}
        {{-- @dd($floors); --}}
        @if ($floors->count() > 0)
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        {{ __("Floor's") }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Floor Information') }}</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Floor Type') }}</th>
                                    <th scope="col">{{ __('Area(sqft)') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($floors as $floor)
                                    <tr id="floor-row-{{ $floor->id }}">
                                        <td class="f-name">{{ $floor->name }}</td>
                                        <td class="f-type-text">{{ $floor->floor_type->title ?? '-' }}</td>
                                        <td class="f-area">{{ $floor->area }} </td>
                                        <td class="text-center">

                                            <button type="button" class="btn btn-sm btn-primary btn-edit-floor"
                                                title="Edit" data-id="{{ $floor->id }}"
                                                data-name="{{ $floor->name }}" data-type-id="{{ $floor->floor_type_id }}"
                                                data-type-text="{{ $floor->floor_type->title ?? '' }}"
                                                data-area="{{ $floor->area }}">
                                                <i class="fa fa-edit"></i>
                                            </button>


                                            <form action="{{ route('maintainer.floor.destroy', $floor->id) }}"
                                                method="POST" style="display:inline-block;"
                                                onsubmit="return confirm('Are you sure you want to delete this floor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        @endif


        {{-- Units List --}}

        @if ($units->count() > 0)
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        {{ __("Unit's") }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Unit Information') }}</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Floor') }}</th>
                                    <th scope="col">{{ __('Area(sqft)') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                    <tr id="floor-row-{{ $unit->id }}">
                                        <td class="f-name">{{ $unit->name }}</td>
                                        <td class="f-type-text">{{ $unit->floors->name ?? '-' }}</td>
                                        <td class="f-area">{{ $unit->area }} </td>
                                        <td class="text-center">

                                            <button type="button" class="btn btn-sm btn-primary btn-edit-unit"
                                                title="Edit" data-id="{{ $unit->id }}"
                                                data-name="{{ $unit->name }}" data-floor-id="{{ $unit->floor_id }}"
                                                data-floor-name="{{ $unit->floor->name ?? '' }}"
                                                data-area="{{ $unit->area }}" data-remarks="{{ $unit->remarks ?? '' }}"
                                                data-building-id="{{ $building->id ?? $unit->building_id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>



                                            <form action="{{ route('maintainer.units.destroy', $unit->id) }}"
                                                method="POST" style="display:inline-block;"
                                                onsubmit="return confirm('Are you sure you want to delete this unit?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>
        @endif

    </div>



    @php
        $sumOccupiedArea = 0;
        foreach ($floors as $key => $f) {
            $sumOccupiedArea += $f->area;
        }
    @endphp

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
                {!! Form::open([
                    'route' => ['maintainer.building.floor.store', $buildings->id],
                    'method' => 'POST',
                    'id' => 'ajaxFloorForm',
                ]) !!}
                <div class="modal-body">
                    <input type="hidden" name="building_id" value="{{ $buildings->id }}" id="building_id">

                    <div class="form-group">
                        {{ Form::label('name', __('Floor Name')) }}
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter floor name'), 'required' => true]) }}
                    </div>



                    <div class="form-group">
                        {{ Form::label('area', __('Area (sqft)')) }}
                        <div class="badge badge-primary" id="totalArea" style="background:#216464">Total Area:
                            {{ $buildings->area }}</div>
                        <div class="badge badge-primary" id="occupiedArea" style="background:#3d4e8d">Occupied Area:
                            {{ $sumOccupiedArea }}</div>
                        {{ Form::number('area', null, ['class' => 'form-control area', 'placeholder' => __('Enter floor area'), 'step' => '0.1', 'required' => true]) }}
                        <span class="error-danger" class="badge-danger"></span>
                    </div>

                    <div class="form-group">
                        {{ Form::label('floor_type_id', __('Floor Type')) }}
                        {{ Form::select('floor_type_id', $floor_type, null, ['class' => 'form-control', 'required' => true, 'placeholder' => __('Select Floor Type')]) }}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info closeModalBtn"
                        data-dismiss="modal">{{ __('Close') }}</button>
                    {{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!--Start Edit Floor Modal -->
    <div class="modal fade" id="editFloorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="editFloorForm">
                    @csrf
                    <input type="hidden" id="efloor_id" name="id">
                    <input type="hidden" name="_method" value="PUT">

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Floor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" id="efloor_name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label class="form-label" style="display: inline;">Area (sqft)</label>
                            <div class="badge badge-primary" id="totalArea" style="background:#216464">Total Area:
                                {{ $buildings->area }}</div>
                            <div class="badge badge-primary" id="occupiedArea" style="background:#3d4e8d">Occupied Area:
                                {{ $sumOccupiedArea }}</div>
                            <input type="number" step="0.01" id="efloor_area" name="area"
                                class="form-control area">
                            <span class="error-danger" class="badge-danger"></span>


                        </div>

                        <div class="mb-3">
                            <label class="form-label">Floor Type</label>
                            <select id="efloor_type_id" name="floor_type_id" class="form-control select2">
                                <option value="">-- Select --</option>
                                @foreach ($floor_type as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="editFloorErrors" class="text-danger small d-none"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="editFloorSubmit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="editFloorSpinner"></span>
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Create Unit Models unitCustomModal --}}
    <div class="modal fade" id="UnitcustomModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document"> <!-- small modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add Unit') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- Form Inside Modal --}}
                {!! Form::open([
                    'route' => ['maintainer.building.units.store', $buildings->id],
                    'method' => 'POST',
                    'id' => 'ajaxUnitForm',
                ]) !!}
                <div class="modal-body">
                    <input type="hidden" name="building_id" value="{{ $buildings->id }}" id="building_id">

                    <div class="form-group">
                        {{ Form::label('name', __('Name')) }}
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => true]) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('floor_id', __('Floor')) }}
                        {{ Form::select('floor_id', $dataFloors, null, [
                            'class' => 'form-control select2',
                            'id' => 'floor_id',
                            'required' => true,
                            'placeholder' => __('Select Floor'),
                        ]) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('area', __('Area (sqft)')) }}

                        {{-- You can rename badges to reflect per-floor --}}
                        <div class="badge badge-primary" id="badgeFloorArea" style="background:#216464">
                            Total Area: —
                        </div>
                        <div class="badge badge-primary" id="badgeOccupiedArea" style="background:#3d4e8d">
                            Occupied: —
                        </div>


                        {{ Form::number('area', null, [
                            'class' => 'form-control unit_area',
                            'id' => 'unit_area',
                            'placeholder' => __('Enter unit area'),
                            'step' => '0.1',
                            'required' => true,
                        ]) }}

                        <span class="error-dangers text-danger small d-block mt-1" id="areaError"></span>
                    </div>




                    <div class="form-group">
                        {{ Form::label('remarks', __('Remarks')) }}
                        {{ Form::textarea('remarks', null, [
                            'class' => 'form-control',
                            'placeholder' => __('Enter Remarks here...'),
                            'rows' => 3,
                            'required' => true,
                        ]) }}
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info closeModalBtn"
                        data-dismiss="modal">{{ __('Close') }}</button>
                    {{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{-- Update Unit Models btn-edit-unit editUnitModal --}}

    <div class="modal fade" id="editUnitModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form id="editUnitForm">
                    @csrf
                    <input type="hidden" id="eunit_id" name="id">
                    <input type="hidden" id="eunit_building_id" name="building_id">
                    <input type="hidden" name="_method" value="PUT">

                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit Unit') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="eunit_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Floor') }} <span class="text-danger">*</span></label>
                            <select name="floor_id" id="eunit_floor_id" class="form-control select2" required>
                                <option value="">{{ __('Select Floor') }}</option>
                                @foreach ($floors as $f)
                                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">{{ __('Area (sqft)') }} <span class="text-danger">*</span></label>

                            <div class="d-flex gap-2 flex-wrap mb-2">
                                <div class="badge badge-primary" id="ebadgeFloorArea" style="background:#216464">Total
                                    Total Area: —</div>
                                <div class="badge badge-primary" id="ebadgeOccupiedArea" style="background:#3d4e8d">
                                    Occupied: —</div>
                            </div>

                            <input type="number" name="area" id="eunit_area" class="form-control" step="0.1"
                                required>
                            <span class="text-danger small d-block mt-1" id="eareaError"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Remarks') }}</label>
                            <textarea name="remarks" id="eunit_remarks" class="form-control" rows="3"
                                placeholder="{{ __('Enter Remarks here...') }}"></textarea>
                        </div>

                        <div id="editUnitErrors" class="text-danger small d-none"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="editUnitSubmit">
                            <span class="spinner-border spinner-border-sm d-none" id="editUnitSpinner"></span>
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection

@section('js')
    <script>
        $(document).ready(function() {


            $(".area").on("change keyup", function() {
                var totalArea = parseFloat($("#totalArea").text().replace(/[^\d.]/g, '')) || 0;
                var occupiedArea = parseFloat($("#occupiedArea").text().replace(/[^\d.]/g, '')) || 0;
                var area = parseFloat($(this).val()) || 0;
                $(".error-danger").html("");
                if ((occupiedArea + area) > totalArea) {
                    $(".error-danger").html("⚠ The total floor area exceeds the building area.");
                    $(this).val(""); // reset field
                }
            });

            $('#openFloorModal').on('click', function(e) {
                e.preventDefault();
                $('#FloorcustomModal').modal('show');
            });

            $('.closeModalBtn').on('click', function(e) {
                e.preventDefault();
                $('#FloorcustomModal').modal('hide');
            });

            $('#ajaxFloorForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    url: form.attr('action'),
                    type: "POST",
                    data: formData,
                    success: function(res) {

                        toastr.success('Type updated successfully.');
                        $('#FloorcustomModal').modal('hide');
                        location.reload(); // refresh list

                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    }
                });
            });

            // Floor Update
            $(document).on('click', '.btn-edit-floor', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const typeId = $(this).data('type-id') || '';
                const area = $(this).data('area') || '';

                $('#efloor_id').val(id);
                $('#efloor_name').val(name);
                $('#efloor_type_id').val(typeId).trigger('change');
                $('#efloor_area').val(area);

                $('#editFloorErrors').addClass('d-none').empty();
                $('#editFloorModal').modal('show');
            });


            $('#editFloorForm').on('submit', function(e) {
                e.preventDefault();

                const id = $('#efloor_id').val();

                let url = "{{ route('maintainer.floor.update', '__ID__') }}".replace('__ID__', id);

                const $btn = $('#editFloorSubmit');
                const $spin = $('#editFloorSpinner');
                $btn.prop('disabled', true);
                $spin.removeClass('d-none');

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(resp) {
                        $('#editFloorModal').modal('hide');
                        if (resp && resp.floor) {
                            const row = $('#floor-row-' + resp.floor.id);
                            row.find('.f-name').text(resp.floor.name);
                            row.find('.f-type-text').text(resp.floor.floor_type_title || '-');
                            row.find('.f-area').text(resp.floor.area ?? '');
                            // Also update button data-* so next edit shows fresh values
                            row.find('.btn-edit-floor')
                                .data('name', resp.floor.name)
                                .data('type-id', resp.floor.floor_type_id)
                                .data('type-text', resp.floor.floor_type_title || '')
                                .data('area', resp.floor.area ?? '');
                        }

                        if (window.toastr) toastr.success('Floor updated successfully');
                    },
                    error: function(xhr) {
                        let msg = 'Update failed.';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errs = xhr.responseJSON.errors;
                            const $err = $('#editFloorErrors').removeClass('d-none').empty();
                            Object.keys(errs).forEach(function(k) {
                                $err.append(`<div>• ${errs[k][0]}</div>`);
                            });
                            msg = '';
                        }
                        if (msg && window.toastr) toastr.error(msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                        $spin.addClass('d-none');
                    }
                });
            });

            // Create Unit Models and Validation's
            $('#unitCustomModal').on('click', function(e) {
                e.preventDefault();
                $('#UnitcustomModal').modal('show');
            });

            $('.closeModalBtn').on('click', function(e) {
                e.preventDefault();
                $('#UnitcustomModal').modal('hide');
            });

            $('#ajaxUnitForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    url: form.attr('action'),
                    type: "POST",
                    data: formData,
                    success: function(res) {

                        toastr.success('Unit Create successfully.');
                        $('#UnitcustomModal').modal('hide');
                        location.reload(); // refresh list

                    },
                    error: function(xhr) {
                        $('#UnitcustomModal').modal('hide');
                        Swal.fire('Error', 'Something went wrong', 'error');
                    }
                });
            });

            $("#floor_id").on("change", function() {
                const id = $(this).val();
                const url = "{{ route('maintainer.building.floor.area', '__ID__') }}".replace('__ID__',
                    id);
                $.ajax({
                    url: url,
                    method: "GET",
                    dataType: "json",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $('#badgeFloorArea').text('Total Area: ' + data.floor_area);
                        $('#badgeOccupiedArea').text('Occupied: ' + data.occupied_area);

                    },
                });

            });


            $(".unit_area").on("change keyup", function() {
                var badgeFloorArea = parseFloat($("#badgeFloorArea").text().replace(/[^\d.]/g, '')) || 0;
                var badgeOccupiedArea = parseFloat($("#badgeOccupiedArea").text().replace(/[^\d.]/g, '')) ||
                    0;
                var area = parseFloat($(this).val()) || 0;
                $(".error-dangers").html("");
                if ((badgeOccupiedArea + area) > badgeFloorArea) {
                    $(".error-dangers").html("⚠ The total floor area exceeds the floor area.");
                    $(this).val("");
                }
            });


            //Update unit Loogic 
            $(function() {

                function resetBadges() {
                    $('#ebadgeFloorArea').text('Total: —');
                    $('#ebadgeOccupiedArea').text('Occupied: —');
                    $('#ebadgeRemainingArea').text('Remaining: —');
                    $('#eunit_area').removeAttr('max');
                    $('#eareaError').text('');
                }

                function fetchFloorAreaAndCap(floorId, currentArea) {
                    const url = "{{ route('maintainer.building.floor.area', '__ID__') }}".replace('__ID__',
                        floorId);

                    $.getJSON(url, function(r) {
                        const fa = Number(r.floor_area || 0);
                        const oa = Number(r.occupied_area || 0);
                        // add-back current unit area so user can keep/adjust without false exceed
                        let ra = Math.max(0, Number(r.remaining_area || 0) + (parseFloat(
                            currentArea || 0) || 0));

                        $('#ebadgeFloorArea').text('Total: ' + fa.toFixed(2));
                        $('#ebadgeOccupiedArea').text('Occupied: ' + oa.toFixed(2));
                        $('#ebadgeRemainingArea').text('Remaining: ' + ra.toFixed(2));

                        $('#eunit_area').attr('max', ra.toFixed(2));
                        const val = parseFloat($('#eunit_area').val() || 0);
                        if (val > ra) {
                            $('#eunit_area').val(ra.toFixed(2));
                            $('#eareaError').text('Area cannot exceed remaining area (' + ra
                                .toFixed(2) + ').');
                        } else {
                            $('#eareaError').text('');
                        }
                    }).fail(function() {
                        resetBadges();
                        $('#eareaError').text('Could not load floor area.');
                    });
                }

                // Open modal
                $(document).on('click', '.btn-edit-unit', function() {
                    const btn = $(this);

                    $('#eunit_id').val(btn.data('id'));
                    $('#eunit_name').val(btn.data('name'));
                    $('#eunit_floor_id').val(btn.data('floor-id')).trigger('change');
                    $('#eunit_area').val(btn.data('area'));
                    $('#eunit_remarks').val(btn.data('remarks') || '');
                    $('#eunit_building_id').val(btn.data('building-id') || '');

                    resetBadges();

                    const floorId = String(btn.data('floor-id') || '');
                    const currentArea = parseFloat(btn.data('area') || 0);
                    if (floorId) fetchFloorAreaAndCap(floorId, currentArea);

                    $('#editUnitErrors').addClass('d-none').empty();
                    $('#editUnitModal').modal('show');
                });

                // Floor change inside modal
                $('#eunit_floor_id').on('change', function() {
                    const floorId = $(this).val();
                    const currentArea = parseFloat($('#eunit_area').val() || 0);
                    if (!floorId) return resetBadges();
                    fetchFloorAreaAndCap(floorId, currentArea);
                });

                $('#eunit_area').on('input', function() {
                    const max = parseFloat($(this).attr('max'));
                    const val = parseFloat($(this).val() || 0);
                    if (!isNaN(max) && val > max) {
                        $(this).val(max);
                        $('#eareaError').text('Area cannot exceed remaining area (' + max + ').');
                    } else {
                        $('#eareaError').text('');
                    }
                });

                // Submit form (PUT)
                $('#editUnitForm').on('submit', function(e) {
                    e.preventDefault();

                    const id = $('#eunit_id').val();
                    const url = "{{ route('maintainer.units.update', '__ID__') }}".replace(
                        '__ID__', id);

                    const $btn = $('#editUnitSubmit');
                    const $spinner = $('#editUnitSpinner');
                    $btn.prop('disabled', true);
                    $spinner.removeClass('d-none');

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: $(this).serialize() + '&_method=PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(resp) {
                            $('#editUnitModal').modal('hide');
                            toastr.success('Unit updated successfully');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        },
                        error: function(xhr) {
                            toastr.error('Something went wrong');
                        },
                        complete: function() {
                            $btn.prop('disabled', false);
                            $spinner.addClass('d-none');
                        }
                    });
                });

            });
        });
    </script>
@endsection
