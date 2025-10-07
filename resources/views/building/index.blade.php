@extends('admin.layouts.main')
@section('title')
    {{__('Buildings')}}
@stop
@section('content')
    {{-- @can('create property') --}}
        <a class="btn btn-primary" href="{{ route('maintainer.building.create') }}" data-size="md"> 
            <i class="fa fa-plus"></i>{{__('Create Buildings')}}</a>
    {{-- @endcan --}}

    <div class="row mt-1">
    @forelse ($buildings as $bg)
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                {{-- Image --}}
                <img class="card-img-top" 
                     src="{{ !empty($bg->image) ? asset($bg->image) : asset('upload/thumbnail/default.jpg') }}" 
                     alt="{{ $bg->name }}" 
                     style="height:180px; object-fit:cover;">

                <div class="card-body d-flex flex-column">
                    {{-- Title --}}
                    <h5 class="card-title mb-2 text-primary">
                        {{ $bg->name }}
                    </h5>

                    {{-- Area --}}
                    <p class="card-text text-muted mb-1">
                        <i class="fa fa-building mr-1"></i> Area: {{ $bg->area }} m²
                    </p>

                    {{-- Company / Branch --}}
                    <p class="card-text text-muted mb-3">
                        <i class="fa fa-sitemap mr-1"></i> Company: {{ $bg->company->name ?? '-' }} <br>
                        <i class="fa fa-code-branch mr-1"></i> Branch: {{ $bg->branch->name ?? '-' }}
                    </p>

                    {{-- Description --}}
                    <p class="small text-muted flex-grow-1">
                        {{ Str::limit($bg->description, 80, '...') }}
                    </p>

                    {{-- Actions --}}
                    <div class="mt-3 d-flex justify-content-between">
                        <a href="{{ route('maintainer.building.show', $bg->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye"></i> View
                        </a>
                        
                        <a href="{{ route('maintainer.building.edit', $bg->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa fa-edit"></i> Edit
                        </a>

                        {!! Form::open(['method' => 'POST', 'route' => ['maintainer.building.destroy', $bg->id],'id'=>'tenant-'.$bg->id]) !!}
                        <a href="" class="btn btn-sm btn-outline-info confirm_dialog" data-id="{{ $bg->id }}">
                            <i class="fa fa-eye"></i> Delete
                        </a>
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                No buildings found.
            </div>
        </div>
    @endforelse
</div>

</div>
@endsection
@section('js')
<script>



 // Delete type for sweet alerts
    $(document).ready(function () {
    // Delegate event for multiple rows
    $(document).on('click', '.confirm_dialog', function (e) {
        e.preventDefault();

        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('maintainer.building.destroy', ':id') }}".replace(':id', id),
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": "POST"
                    },
                    success: function (response) {
                      
                        Swal.fire(
                            'Deleted!',
                            'Building has been deleted successfully.',
                            'success'
                        );

                        window.location.reload();
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            'Something went wrong. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});

</script>
@endsection
