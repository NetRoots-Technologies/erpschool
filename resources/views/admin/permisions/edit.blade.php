<div class="card-body">

    <div class="form-group">
        <div class="input-label">
            <label>Name</label>
        </div>
        <input type="text" required class="form-control" value="{!! $Permission->name !!}" name="name">
    </div>
    <input type hidden value="{!! $Permission->id !!}" id="edit_id">

    <input type hidden value="{!! route('permissions.update', $Permission->id ) !!}" name="id" id="route_edit">
</div>
@section('js')
<script>

</script>
@endsection