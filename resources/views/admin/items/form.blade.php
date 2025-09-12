@inject('helper', 'App\Helper\helper')
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlInput1">Category</label>

            <select class="form-control category_id" name="category_id" required id="exampleFormControlSelect1">
                <option value="0">Please Select Category</option>
                @foreach($helper::getCategories() as $categorie)
                    <option value="{{$categorie['id']}}"
                            @if(isset($item['category_id'])) @if($item['category_id'] == $categorie['id'])  selected @endif @endif>{{$categorie['name']}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlSelect2">Sub Category</label>
            <div id="getSubCategory_container">
                <select class="form-control" name="sub_category_id" required id="exampleFormControlSelect2">
                    <option value="0">Please Select Sub Category</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlInput1">Code*</label>
            <input type="text" class="form-control" value="{{@$item['code']}}" name="code" required
                   id="exampleFormControlInput1">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="exampleFormControlInput1">Name*</label>
            <input type="text" class="form-control" value="{{@$item['name']}}" name="name" required
                   id="exampleFormControlInput1">
        </div>
    </div>
</div>
<input type="hidden" name="item_type" value="item">
