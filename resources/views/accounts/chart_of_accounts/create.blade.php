@extends('admin.layouts.main')

@section('title', 'Create Account')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create New Account</h4>
            <div class="page-title-right">
                <a href="{{ route('accounts.coa.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('accounts.coa.store') }}" method="POST">
                    @csrf
                    
                    {{-- Level 1 --}}
                    <div class="mb-3">
                        <label>Main Group (Level 1)</label>
                        <select id="level1" class="form-select" name="account_group_id">
                            <option value="">Select</option>
                            @foreach($groups->whereNull('parent_id') as $g)
                                <option value="{{ $g->id }}">{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Level 2 --}}
                    <div class="mb-3" id="level2_div" style="display:none;">
                        <label>Child Group (Level 2)</label>
                        <select id="level2_dropdown" class="form-select" name="parent_id_level2">
                            <option value="">Select</option>
                        </select>
                    </div>

                    {{-- Level 3 --}}
                    <div class="mb-3">
                        <div id="level3_checkbox_div" style="display:none;">
                            <input type="checkbox" id="level3_use_dropdown"> Select existing Level 3
                        </div>
                        <select id="level3_dropdown" class="form-select" style="display:none;" name="parent_id_level3">
                            <option value="">Select</option>
                        </select>
                        <input type="text" id="level3_input" class="form-control" placeholder="Enter Level 3 name" style="display:none;">
                    </div>

                    {{-- Level 4 --}}
                    <div class="mb-3" id="level4_div" style="display:none;">
                        <div id="level4_checkbox_div" style="display:none;">
                            <input type="checkbox" id="level4_use_dropdown"> Select existing Level 4
                        </div>
                        <select id="level4_dropdown" class="form-select" style="display:none;" name="parent_id_level4">
                            <option value="">Select</option>
                        </select>
                        <input type="text" id="level4_input" class="form-control" placeholder="Enter Level 4 name" style="display:none;">
                    </div>

                    {{-- Level 5 --}}
                    <div class="mb-3" id="level5_div" style="display:none;">
                        <label>Final Account Name (Level 5)</label>
                        <input type="text" id="level5_name" class="form-control" placeholder="Enter Account Name" value="{{ old('name') }}"  name="name">
                    </div>

                    {{-- Hidden final name --}}
                    <input type="hidden" name="name" id="final_name">

                         {{-- account_code --}}
                    <div class="mb-3">
                        <label class="form-label">Account code</label>
                        <input type="text" name="code" class="form-control" rows="3"{{ old('code') }}name="code">
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    {{-- Opening Balance --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Opening Balance</label>
                                <input type="number" step="0.01" name="opening_balance" class="form-control" value="{{ old('opening_balance', 0) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Balance Type</label>
                                <select name="opening_balance_type" class="form-select">
                                    <option value="debit" {{ old('opening_balance_type') == 'debit' ? 'selected' : '' }}>Debit</option>
                                    <option value="credit" {{ old('opening_balance_type') == 'credit' ? 'selected' : '' }}>Credit</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Active --}}
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function resetLevel(level){
    for (var i = level; i <= 5; i++) {
        $('#level' + i + '_div, #level' + i + '_dropdown, #level' + i + '_input, #level' + i + '_checkbox_div').hide();
        $('#level' + i + '_dropdown').html('<option value="">Select</option>');
        $('#level' + i + '_input').val('');
        $('#level' + i + '_checkbox_div input[type=checkbox]').prop('checked', false);
    }
}

/* ======================
   LEVEL HANDLING LOGIC
====================== */

// Level 1 → Level 2
$('#level1').on('change', function(){
    const parentId = $(this).val();
    resetLevel(2);

    if (parentId) {
        $.get('{{ route("accounts.coa.getChildGroups") }}', { parent_id: parentId }, function(data){
            $('#level2_div').show();
            if (data.length > 0) {
                $('#level2_dropdown').html('<option value="">Select</option>');
                $.each(data, function(i, v){
                    $('#level2_dropdown').append('<option value="'+v.id+'">'+v.name+'</option>');
                });
                $('#level2_dropdown').show();
            }
        });
    }
});

// Level 2 → Level 3
$('#level2_dropdown').on('change', function(){
    const parentId = $(this).val();
    resetLevel(3);

    if (!parentId) return;

    $.get('{{ route("accounts.coa.getChildGroups") }}', { parent_id: parentId }, function(data){
        $('#level3_checkbox_div').show();
        $('#level3_input').show();
        if (data.length > 0) {
            $('#level3_dropdown').html('<option value="">Select</option>');
            $.each(data, function(i, v){
                $('#level3_dropdown').append('<option value="'+v.id+'">'+v.name+'</option>');
            });
        }
    });
});

// Level 3 checkbox toggle
$('#level3_use_dropdown').on('change', function(){
    const parentId = $('#level2_dropdown').val();
    if ($(this).is(':checked')) {
        $.get('{{ route("accounts.coa.getthirdchild") }}', { parent_id: parentId }, function(data){
            $('#level3_dropdown').html('<option value="">Select</option>');
            $.each(data, function(i, v){
                $('#level3_dropdown').append('<option value="'+v.id+'">'+v.name+'</option>');
            });
            $('#level3_dropdown').show();
            $('#level3_input').hide();
        });
    } else {
        $('#level3_input').show();
        $('#level3_dropdown').hide();
    }
});

// Level 3 → Level 4
$('#level3_dropdown').on('change', function(){
    const parentId = $(this).val();
    resetLevel(4);
    if (!parentId) return;

    $.get('{{ route("accounts.coa.getChildGroups") }}', { parent_id: parentId }, function(data){
        $('#level4_div').show();
        $('#level4_checkbox_div').show();
        $('#level4_input').show();
        if (data.length > 0) {
            $('#level4_dropdown').html('<option value="">Select</option>');
            $.each(data, function(i, v){
                $('#level4_dropdown').append('<option value="'+v.id+'">'+v.name+'</option>');
            });
        }
    });
});

// Level 4 checkbox toggle
$('#level4_use_dropdown').on('change', function(){
    const parentId = $('#level3_dropdown').val();
    if ($(this).is(':checked')) {
        $.get('{{ route("accounts.coa.getChildGroups") }}', { parent_id: parentId }, function(data){
            $('#level4_dropdown').html('<option value="">Select</option>');
            $.each(data, function(i, v){
                $('#level4_dropdown').append('<option value="'+v.id+'">'+v.name+'</option>');
            });
            $('#level4_dropdown').show();
            $('#level4_input').hide();
        });
    } else {
        $('#level4_input').show();
        $('#level4_dropdown').hide();
    }
});

// Level 4 → Level 5 (fixed)
$('#level4_dropdown').on('change', function(){
    const parentId = $(this).val();
    if (!parentId) {
        $('#level5_div').hide();
        return;
    }

    // Always show Level 5 input when a Level 4 is selected
    $('#level5_div').show();

    // Optionally check if there are child groups under selected Level 4
    $.get('{{ route("accounts.coa.getChildGroups") }}', { parent_id: parentId }, function(data){
        if (data.length > 0) {
            // If you want to show a hint that more sub-levels exist:
            // console.log('This Level 4 has children — you may choose an existing Level 5 instead of creating a new one.');
            // You could also display a dropdown for existing Level 5 items here if desired.
        } else {
            // No deeper groups; user likely will create the final account (Level 5)
        }
    });
});

// If user is typing a NEW Level 4 name, show Level 5 so they can enter the final account name
$('#level4_input').on('keyup', function(){
    // Show level5 so user can immediately enter final account name for the newly created Level 4
    $('#level5_div').show();
});



/* ======================
   UPDATE FINAL NAME
====================== */
function updateFinalName() {
    let nameVal = '';

    if ($('#level5_name').is(':visible') && $('#level5_name').val()) {
        nameVal = $('#level5_name').val();
    } else if ($('#level4_input').is(':visible') && $('#level4_input').val()) {
        nameVal = $('#level4_input').val();
    } else if ($('#level3_input').is(':visible') && $('#level3_input').val()) {
        nameVal = $('#level3_input').val();
    }

    $('#final_name').val(nameVal);
}

$('#level3_input, #level4_input, #level5_name').on('keyup change', updateFinalName);

</script>
@endsection
