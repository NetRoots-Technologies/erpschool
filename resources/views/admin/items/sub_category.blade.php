<select class="form-control" name="sub_category_id" required id="exampleFormControlSelect2">
    <option value="0">Please Select Sub Category</option>
    @foreach($sub_category as $sub_categorie)
        <option value="{{$sub_categorie['id']}}" @if(isset($sub_cat_val)) @if($sub_cat_val == $sub_categorie['id'])  selected @endif @endif>{{$sub_categorie['name']}}</option>
    @endforeach
</select>
