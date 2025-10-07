@extends('admin.layouts.main')

@section('title', 'Maintenance Request Update')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="card basic-form">
        <div class="card-body">
          <h3 class="text-22 text-midnight text-bold mb-4">Update Maintenance Request</h3>

          <form action="{{ route('maintenance-request.update', $model->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
              {{-- Building --}}
              <div class="col-md-3 mb-3">
                <label>Building <span class="text-danger">*</span></label>
                <select name="building_id" id="building_id" class="form-control select2" required>
                  <option value="">--Select--</option>
                  @foreach ($buildings as $id => $name)
                    <option value="{{ $id }}" {{ $model->building_id == $id ? 'selected' : '' }}>
                      {{ $name }}
                    </option>
                  @endforeach
                </select>
              </div>

              {{-- Unit --}}
              <div class="col-md-3 mb-3">
                <label>Unit <span class="text-danger">*</span></label>
                <select name="unit_id" id="unit_id" class="form-control select2" required>
                  @foreach ($units as $id => $name)
                    <option value="{{ $id }}" {{ $model->unit_id == $id ? 'selected' : '' }}>
                      {{ $name }}
                    </option>
                  @endforeach
                </select>
              </div>

              {{-- Issue Type --}}
              <div class="col-md-3 mb-3">
                <label>Issue Type <span class="text-danger">*</span></label>
                <select name="issue_type" class="form-control select2" required>
                  <option value="">--Select--</option>
                  @foreach ($issueTypes as $id => $title)
                    <option value="{{ $id }}" {{ $model->issue_type == $id ? 'selected' : '' }}>
                      {{ $title }}
                    </option>
                  @endforeach
                </select>
              </div>

              {{-- Maintainer --}}
              <div class="col-md-3 mb-3">
                <label>Maintainer <span class="text-danger">*</span></label>
                <select name="maintainer_id" class="form-control select2" required>
                  <option value="">--Select--</option>
                  @foreach ($maintainerUsers as $id => $name)
                    <option value="{{ $id }}" {{ $model->maintainer_id == $id ? 'selected' : '' }}>
                      {{ $name }}
                    </option>
                  @endforeach
                </select>
              </div>

              {{-- Request Date --}}
              <div class="col-md-3 mb-3">
                <label>Request Date <span class="text-danger">*</span></label>
                <input type="date" name="request_date" class="form-control"
                  value="{{ old('request_date', \Carbon\Carbon::parse($model->request_date)->format('Y-m-d')) }}"
                  required>
              </div>

              {{-- Attachment --}}
              <div class="col-md-9 mb-3">
                <label>Attachment (Image)</label>
                <input type="file" name="issue_attachment" class="form-control" accept="image/*">
                @if ($model->issue_attachment)
                  <small class="d-block mt-1">
                    Current: 
                    <a href="{{ asset('issue_attachment/' . $model->issue_attachment) }}" target="_blank">
                      {{ $model->issue_attachment }}
                    </a>
                  </small>
                @endif
              </div>
            </div>

            {{-- Notes full width --}}
            <div class="row mt-2">
              <div class="col-12">
                <label>Notes</label>
                <textarea name="notes" rows="4" class="form-control">{{ old('notes', $model->notes) }}</textarea>
              </div>
            </div>

            <div class="text-right mt-4">
              <button type="submit" class="btn btn-primary">Update</button>
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
        $(function() {

            // load units if old values exist (form re-render after validation error)
            const oldBuilding = "{{ old('building_id') }}";
            const oldUnit = "{{ old('unit_id') }}";
            if (oldBuilding) {
                loadUnits(oldBuilding, oldUnit);
            }

            // when building changes
            $('#building_id').on('change', function() {
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
                    success: function(units) {
                        $unitSelect.empty(); // clear old options
                        $unitSelect.append('<option value="">-- Select Unit --</option>');

                        // if list is not empty
                        if (Array.isArray(units) && units.length > 0) {
                            $.each(units, function(index, u) {
                                const selected = (preselectId && String(preselectId) === String(
                                    u.id)) ? 'selected' : '';
                                $unitSelect.append(
                                    `<option value="${u.id}" ${selected}>${u.name}</option>`
                                );
                            });
                        } else {
                            $unitSelect.append('<option value="">No units found</option>');
                        }

                        // refresh select2 (if used)
                        $unitSelect.trigger('change.select2');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $unitSelect.html('<option value="">-- Select Unit --</option>');
                        toastr.error('Failed to load units for selected building');
                    }
                });
            }

        });
    </script>
@endsection
