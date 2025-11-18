@extends('admin.layouts.main')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="m-0">Student Transportation</h3>

            <div class="d-flex">

                @if (Gate::allows('students-transport-create'))
                    <a href="{{ route('fleet.transportation.create') }}" 
                       class="btn btn-primary btn-sm me-2">
                        <i class="fa fa-plus"></i> Assign Transportation
                    </a>
                @endif

                @if (Gate::allows('students-transport-create'))
                    <a href="#" class="btn btn-success btn-sm me-2"
                       data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fa fa-file-import"></i> Import Transport Assign
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Import Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form action="{{ route('fleet.transportation.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Import Excel File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Select File</label>
                            <input type="file" name="import_file" class="form-control" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Data Table --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Vehicle</th>
                        <th>Route</th>
                        <th>Charges</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transportations as $t)
                        <tr>
                            <td>{{ $t->id }}</td>

                            <td>
                                <strong>{{ $t->student->first_name }} {{ $t->student->last_name }}</strong><br>
                                <small class="text-muted">{{ $t->student->student_id }}</small>
                            </td>

                            <td>{{ $t->student->AcademicClass->name ?? 'N/A' }}</td>

                            <td>
                                {{ $t->vehicle->vehicle_number }} <br>
                                <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $t->vehicle->vehicle_type)) }}</small>
                            </td>

                            <td>
                                {{ $t->route->route_name }}<br>
                                <small class="text-muted">{{ $t->pickup_point }} → {{ $t->dropoff_point }}</small>
                            </td>

                            <td>
                                <strong>Rs. {{ number_format($t->monthly_charges, 2) }}</strong><br>
                                <small class="text-muted">per month</small>
                            </td>

                            <td>
                                @if ($t->status == 'active')
                                    <span class="badge bg-success text-dark">Active</span>
                                @else
                                    <span class="badge bg-secondary text-dark">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group">

                                    @if (Gate::allows('students-transport-view'))
                                        <a href="{{ route('fleet.transportation.show', $t->id) }}"
                                           class="btn btn-info btn-sm" title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endif

                                    @if (Gate::allows('students-transport-edit'))
                                        <a href="{{ route('fleet.transportation.edit', $t->id) }}"
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endif

                                    @if (Gate::allows('students-transport-create'))
                                        <form action="{{ route('fleet.transportation.destroy', $t->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No transportation assignments found</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection

@section('js')
<script>
    // Make sure toastr library is included in layout (CSS + JS)
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('error'))
            // Use html enabled option so <br> works
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "10000",
                "extendedTimeOut": "2000",
                "escapeHtml": false
            };
            toastr.error("{!! session('error') !!}");
        @endif

        @if(session('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "5000",
                "extendedTimeOut": "1000"
            };
            toastr.success("{{ session('success') }}");
        @endif
    });
</script>
@endsection
