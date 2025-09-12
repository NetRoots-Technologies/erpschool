<form method="post"
      enctype="multipart/form-data" id="create-form-submit">
    @csrf
<div class="row">
    <div class="row mt-3 ml-2 mr-2">
        <div class="col-6">
            <div class="form-group">
                <div class="input-label">
                    <label>Name</label>
                </div>
                <input type="text" required class="form-control" value="" name="name">
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <div class="input-label">
                    <label>Description</label>
                </div>
                <input type="text-area" required class="form-control" value=" " name="description">
            </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <div class="form-group text-right">
            <input id="create-form-submit" type="submit" class="btn btn-primary btn-sm " data-dismiss="modal"
                   value="Submit ">

            <button type="button" class=" btn btn-sm btn-danger modalclose"
                    data-dismiss="modal">Cancel
            </button>

        </div>
    </div>
</div>
</form>

