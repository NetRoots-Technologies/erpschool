<div class="row">
    <!-- Name Field -->
    <div class="col-xs-6 form-group">
        <label for="name" class="control-label">Name*</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" maxlength="200" required>
        @if($errors->has('name'))
            <span class="help-block text-danger">
                {{ $errors->first('name') }}
            </span>
        @endif
    </div>

    <!-- Balance Field -->
    <div class="col-xs-6 form-group">
        <label for="balance" class="control-label">Opening Balance</label>
        <input type="number" name="balance" id="balance" value="{{ old('balance') }}" class="form-control" step="0.01" min="0">
        @if($errors->has('balance'))
            <span class="help-block text-danger">
                {{ $errors->first('balance') }}
            </span>
        @endif
    </div>

    <!-- Parent Group Dropdown -->
    <div class="col-xs-12 form-group">
        <label for="parent_id" class="control-label">Parent Group</label>
        <select name="parent_id" id="parent_id" class="form-control select2" style="width: 100%;" required>
            {!! $Groups !!}
        </select>
        <span id="parent_id_handler"></span>
        @if($errors->has('parent_id'))
            <span class="help-block text-danger">
                {{ $errors->first('parent_id') }}
            </span>
        @endif
    </div>
</div>
