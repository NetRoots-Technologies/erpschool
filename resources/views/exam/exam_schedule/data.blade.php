<div class="row mt-3">
    <div class="col-md-3">
        <label for="branches"><b>Subjects: *</b></label>
       @if($classSubject->isEmpty())
            <select class="form-select" required>
                <option value="" selected>Please Select Subject</option>
            </select>
        @else
            <select name="subject_id"
                    class="form-select select2 basic-single"
                    aria-label=".form-select-lg example" required>
                @foreach($classSubject as $item)
                    <option value="{{ $item->subject->id }}">{{ $item->subject->name }}</option>
                @endforeach
            </select>
        @endif
    </div>

    <div class="col-md-3">
        <label for="branches"><b>Component: *</b></label>
        <select name="component_id"
                class="form-select select2 basic-single mt-3"
                aria-label=".form-select-lg example">
            @foreach($components as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label for="branches"><b>Marks: *</b></label>
        <input type="text" class="form-control" name="marks" id="marks">
    </div>

    <div class="col-md-1 mt-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="grade" id="grade">
            <label class="form-check-label" for="grade">
                Grade
            </label>
        </div>
    </div>
    <div class="col-md-1 mt-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="pass" id="pass">
            <label class="form-check-label" for="pass">
                Pass
            </label>
        </div>
    </div>
</div>

<script>
    $('.basic-single').select2();
</script>
