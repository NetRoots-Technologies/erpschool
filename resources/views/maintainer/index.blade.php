@extends('admin.layouts.main')
@section('title')
    {{__('Maintainer')}}
@stop

@section('content')

    @can('create maintainer')
        <a class="btn btn-primary" href="{{ route('maintainer.maintainer.create') }}"> <i class="fa fa-plus"></i>{{__('Add Maintainer')}}</a>
    @endcan

    <div class="row mt-1">
        @foreach($maintainers as $maintainer)
            <div class="col-xl-6 col-md-6 cdx-xxl-50 cdx-xl-50">
                <div class="card custom contact-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="user-imgwrapper">
                                <img class="img-fluid" style="width: 50px; height: 50px; margin: 0px 8px 10px -4px;"
                                     src="{{(!empty($maintainer->user) && !empty($maintainer->user->profile))? asset(Storage::url("upload/profile/".$maintainer->user->profile)): asset(("image/avatar.png"))}}"
                                     alt="">
                            </div>
                            <div class="media-body">
                                <a class="customModal" href="#" data-size="md"
                                   data-url="{{ route('maintainer.maintainer.edit',$maintainer->id) }}"  data-title="{{__('Edit Maintainer')}}">
                                    <h4>{{!empty($maintainer->user)?ucfirst($maintainer->user->name):'-'}}</h4>
                                    <h6 class="text-dark">{{!empty($maintainer->user)?$maintainer->user->email:'-'}}</h6>
                                </a>
                            </div>
                            @if(Gate::check('edit maintainer') || Gate::check('delete maintainer') || Gate::check('manage maintainer'))
                                <div class="user-setting">
                                    <div class="action-menu">
                                        <div class="action-toggle"><i data-feather="more-vertical"></i></div>
                                        <ul class="action-dropdown">
                                            @can('edit maintainer')
                                                <li>
                                                    <a href="{{ route('maintainer.maintainer.edit',$maintainer->id) }}" data-size="lg"
                                                       data-url="{{ route('maintainer.maintainer.edit',$maintainer->id) }}"
                                                       data-title="{{__('Edit Maintainer')}}"> <i
                                                            data-feather="edit"> </i>{{__('Edit Maintainer')}}</a>
                                                </li>
                                            @endcan
                                            @can('delete maintainer')
                                                <li>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['maintainer.maintainer.destroy', $maintainer->id],'id'=>'tenant-'.$maintainer->id]) !!}
                                                    <a href="#" class="confirm_dialog" data-id="{{ $maintainer->id }}">
                                                        <i data-feather="trash"></i>{{__('Delete Maintainer')}}
                                                    </a>
                                                {!! Form::close() !!}
                                                </li>
                                            @endcan

                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="user-detail">
                            <h5 class="text-primary mb-10"><i class="fa fa-info-circle mr-10"></i>{{__('Infomation')}}
                            </h5>
                            <ul class="info-list" style="padding: 11px 3px 0px 17px;">
                                {{-- <li><span>{{__('Phone')}} : </span>{{!empty($maintainer->phone_number)?$maintainer->user->phone_number:'-'}} </li> --}}

                                <li>
                                    <strong>{{__('Type')}} : </strong>{{!empty($maintainer->types)?$maintainer->types->title:'-'}}
                                </li>
                                <li>
                                    <strong>{{__('Created Date')}} : </strong>{{date('D-m-Y h:i:A', strtotime($maintainer->created_at));}}
                                </li>
                                <li>
                                    <strong>{{__('Branch')}} : </strong><br>
                                    @foreach($maintainer->branches() as $b)
                                        {{$b->name}}<br>
                                    @endforeach

                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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
                    url: "{{ route('maintainer.maintainer.destroy', ':id') }}".replace(':id', id),
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "_method": "DELETE"
                    },
                    success: function (response) {
                      
                        Swal.fire(
                            'Deleted!',
                            'Maintainer has been deleted successfully.',
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



