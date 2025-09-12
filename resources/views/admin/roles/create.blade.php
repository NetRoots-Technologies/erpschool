<form method="post"
      enctype="multipart/form-data" id="rolecreate">
    @csrf
    <div class="container">
        <div class="col-12">
            <div class="form-group">
                <div class="input-label">
                    <label>Name</label>
                </div>
                <input type="text" required class="form-control" value=" " name="name">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            @foreach($permissions as $item)
                <div class="col-6">
                    <div class="card mt-4 z-50">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span>{!! $item->name !!}</span>
                                <span class="float-right mt-1">
                                                <input type="checkbox" class="parent-checkbox" value="{!! $item->id !!}"
                                                       name="permisions[]">   </span></h5>
                        </div>
                        <div class="card-body">
                            <div class="card-text">
                                <table class="table table-striped table-hover">
                                    @if($item->child !=null)
                                        @foreach($item->child as $item1 )
                                            <tr>
                                                <td> {!! $item1 ->name !!} </td>
                                                <td>
                                                    <span class="ml-4">
                                                     <input type="checkbox" class="child-checkbox" value="{!! $item1->id !!}"
                                                                                name="permisions[]">
                                                                            </span>
                                                                            </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>

                        </div>

                    </div>
                </div>   @endforeach
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group text-center">
                <input id="create-form-submit" type="submit" class="btn btn-primary btn-sm "
                       value="Submit ">

                <button type="button" class=" btn btn-sm btn-danger modalclose"
                        data-dismiss="modal">Cancel
                </button>

            </div>
        </div>
    </div>
</form>


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


