@extends('admin.layouts.main')

@section('title')
    Maintenance Request Create
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card basic-form">
                    <div class="card-body">
                        <h3 class="text-22 text-midnight text-bold mb-4">Create Maintenance Request</h3>

                        

                        <form action="{{ route('maintenance-request.store') }}" method="POST" enctype="multipart/form-data"
                            id="form_validation" autocomplete="off">
                            @csrf

                            <div class="row">
                                {{-- Building --}}
                                <div class="col-lg-6 mb-3">
                                    <label for="building_id">Building <span class="text-danger">*</span></label>
                                    <select name="building_id" id="building_id" class="form-control select2" required>
                                        <option value="">--Select One--</option>
                                        @foreach ($buildings as $id => $name)
                                            <option value="{{ $id }}" {{ old('building_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('building_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Unit (depends on Building) --}}
                                <div class="col-lg-6 mb-3">
                                    <label for="unit_id">Unit <span class="text-danger">*</span></label>
                                    <select name="unit_id" id="unit_id" class="form-control select2" required>
                                        <option value="">--Select Unit--</option>
                                    </select>
                                    @error('unit_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                {{-- Request Date --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="request_date">Request Date <span class="text-danger">*</span></label>
                                    <input type="date" name="request_date" id="request_date" class="form-control"
                                        value="{{ old('request_date', now()->format('Y-m-d')) }}" required>
                                    @error('request_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Issue Type --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="issue_type">Issue Type <span class="text-danger">*</span></label>
                                    <select name="issue_type" id="issue_type" class="form-control select2" required>
                                        <option value="">--Select Issue--</option>
                                        @foreach ($issueTypes as $id => $title)
                                            <option value="{{ $id }}"
                                                {{ old('issue_type') == $id ? 'selected' : '' }}>{{ $title }}</option>
                                        @endforeach
                                    </select>
                                    @error('issue_type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Maintainer --}}
                                <div class="col-lg-4 mb-3">
                                    <label for="maintainer_id">Maintainer <span class="text-danger">*</span></label>
                                    <select name="maintainer_id" id="maintainer_id" class="form-control select2" required>
                                        <option value="">--Select Maintainer--</option>
                                        @foreach ($maintainerUsers as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('maintainer_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('maintainer_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                {{-- Image --}}
                                <div class="col-lg-6 mb-3">
                                    <label for="issue_attachment">Issue Attachment (optional)</label>
                                    <input type="file" name="issue_attachment" id="issue_attachment" class="form-control"
                                        accept="issue_attachment/*">
                                    <small class="text-muted">JPG/PNG, up to ~2MB</small>
                                    @error('issue_attachment')
                                        <small class="text-danger d-block">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Notes --}}
                                <div class="col-lg-6 mb-3">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" rows="4" class="form-control" placeholder="Describe the issue...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="row mt-3">
                                <div class="col-12 text-right">
                                    <button type="submit" class="btn btn-primary">Create Request</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {

    // load units if old values exist (form re-render after validation error)
    const oldBuilding = "{{ old('building_id') }}";
    const oldUnit = "{{ old('unit_id') }}";
    if (oldBuilding) {
        loadUnits(oldBuilding, oldUnit);
    }

    // when building changes
    $('#building_id').on('change', function () {
        const buildingId = $(this).val();
        loadUnits(buildingId, null);
    });

    // reusable function
    function loadUnits(buildingId, preselectId = null) {
        const $unitSelect = $('#unit_id');
        $unitSelect.empty().append('<option value="">Loading...</option>');

        // no building selected
        if (!buildingId) {
            $unitSelect.html('<option value="">-- Select Unit --</option>');
            return;
        }

        // AJAX GET request
        $.ajax({
            url: "{{ route('buildings.units', ':id') }}".replace(':id', buildingId),
            type: "GET",
            dataType: "json",
            success: function (units) {
                $unitSelect.empty(); // clear old options
                $unitSelect.append('<option value="">-- Select Unit --</option>');

                // if list is not empty
                if (Array.isArray(units) && units.length > 0) {
                    $.each(units, function (index, u) {
                        const selected = (preselectId && String(preselectId) === String(u.id)) ? 'selected' : '';
                        $unitSelect.append(`<option value="${u.id}" ${selected}>${u.name}</option>`);
                    });
                } else {
                    $unitSelect.append('<option value="">No units found</option>');
                }

                // refresh select2 (if used)
                $unitSelect.trigger('change.select2');
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                $unitSelect.html('<option value="">-- Select Unit --</option>');
                toastr.error('Failed to load units for selected building');
            }
        });
    }

});

    </script>
@endsection
