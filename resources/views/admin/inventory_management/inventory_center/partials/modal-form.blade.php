{{-- modals --}}
<div class="modal" id="createCategory">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Create Category</h4>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="editform" method="POST" action="{{route('inventory.inventory-center.store')}}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="department_create_label">Title</label>
                                <input type="text" required class="form-control" id="edit_title" name="name">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="department_create_label">Category</label>
                                <select name="category" required id="category" class="form-select select2">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        @if($category->level < 3)
                                            <option value="{{$category->id}}">
                                                {!! str_repeat('&nbsp; &nbsp;', $category->level) !!}{{$category->code . ' - ' . $category->name}}
                                            </option>
                                        @endif;
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
