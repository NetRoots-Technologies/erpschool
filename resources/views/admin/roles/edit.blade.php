<div class="card-body">

    <div class="form-group">
        <div class="input-label">
            <label>Name</label>
        </div>
        <input type="text" required class="form-control" id="name" value="{!! $role->name !!}" name="name">

        <input type="hidden" value="{!! $role->id !!}" name="id" id="id">

    </div>
    <div class="container mt-4">
        <div class="row">
            @foreach($permissions as $item)
            <div class="card col-5 m-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <label for="{{$item->name}}">{!! $item->name !!}</label>
                        <span class="float-right mt-1">
                            <input type="checkbox" class="parent-checkbox" value="{!! $item->id !!}" name="permisions[]" id="{{$item->name}}"
                                @if(isset($AllowedPermissions[$item->id])) checked="true" @endif> </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="card-text">
                        <table class="table table-striped table-hover">
                            @if($item->child !=null)
                            @foreach($item->child as $item1 )
                            <tr>
                                <td> <label> {!! $item1 ->name !!}<label> </td>
                                <td> <span class="ml-4"> <input type="checkbox" class="child-checkbox"
                                            value="{!! $item1->id !!}" @if(isset($AllowedPermissions[$item1->id]))
                                        checked="true"
                                        @endif
                                        name="permisions[]"> </span>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>


</div>

<!-- jQuery code to manage checkboxes -->
<script>
    $(document).ready(function() {
        $('.parent-checkbox').on('change', function() {
            $(this).closest('.card').find('.child-checkbox').prop('checked', this.checked);
        });

        $('.child-checkbox').on('change', function() {
            var $card = $(this).closest('.card');


            var allChecked = $card.find('.child-checkbox:checked').length > 0;
            $card.find('.parent-checkbox').prop('checked', allChecked);
        });
    });
</script>