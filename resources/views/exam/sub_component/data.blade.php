<style>
    .btn-delete {
        color: red;
        cursor: pointer;
    }

    .validation-error {
        color: red;
        font-size: 13px;
        margin-top: 5px;
        display: none;
    }
</style>

@if($component == null)
    <p>Please select a component.</p>
@else
    <table id="users-table" class="table table-bordered table-striped datatable mt-3" style="width: 100%">
        <thead>
        <tr>
            <th>Component</th>
            <th>Name</th>
            <th>Marks</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <select name="test_type_id[]" class="form-select select2 basic-single mt-3 comp">
                    @foreach($component->componentData as $compData)
                        <option 
                            value="{{ $compData->test_type->id }}" 
                            data-marks="{{ $compData->total_marks }}">
                            {{ $compData->test_type->name . ' | ' . $compData->weightage . '%' }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" name="comp_name[]" class="form-control" required>
            </td>
            <td>
                <input type="number" name="comp_number[]" class="form-control marks-field" required>
                <div class="validation-error">Exceeds allowed marks for this component.</div>
            </td>
            <td>
                <button class="btn btn-success btn-add-more">Add More</button>
            </td>
        </tr>
        </tbody>
    </table>
@endif

<script>
    $(document).ready(function () {
        $('.select2').select2();

        // Template for new row
        function getNewRowHtml() {
            return `
                <tr>
                    <td>
                        <select name="test_type_id[]" class="form-select select2 basic-single mt-3 comp">
                            @foreach($component->componentData as $compData)
                                <option 
                                    value="{{ $compData->test_type->id }}" 
                                    data-marks="{{ $compData->total_marks }}">
                                    {{ $compData->test_type->name . ' | ' . $compData->weightage . '%' }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="comp_name[]" class="form-control" required>
                    </td>
                    <td>
                        <input type="number" name="comp_number[]" class="form-control marks-field" required>
                        <div class="validation-error">Exceeds allowed marks for this component.</div>
                    </td>
                    <td>
                        <span class="btn-delete"><i class="fa fa-trash" aria-hidden="true"></i></span>
                    </td>
                </tr>
            `;
        }

        // Add row
        $(document).on('click', '.btn-add-more', function (e) {
            e.preventDefault();
            $('#users-table tbody').append(getNewRowHtml());
            $('.select2').select2();
        });

        // Delete row
        $(document).on('click', '.btn-delete', function () {
            $(this).closest('tr').remove();
            validateAllMarks();
        });

        // Auto-fill marks on component change
        $(document).on('change', '.comp', function () {
            const marks = $(this).find('option:selected').data('marks') || 0;
            $(this).closest('tr').find('.marks-field').val(marks);
            validateAllMarks();
        });

        // Validate all rows to ensure component total doesn't exceed
        function validateAllMarks() {
            const componentTotals = {};

            $('#users-table tbody tr').each(function () {
                const row = $(this);
                const compId = row.find('.comp').val();
                const input = row.find('.marks-field');
                const errorMsg = row.find('.validation-error');
                const val = parseFloat(input.val()) || 0;

                if (!componentTotals[compId]) {
                    componentTotals[compId] = {
                        total: 0,
                        rows: []
                    };
                }

                componentTotals[compId].total += val;
                componentTotals[compId].rows.push({row, val});
            });

            // Check for exceeding marks
            $('#users-table tbody tr').each(function () {
                const row = $(this);
                const compId = row.find('.comp').val();
                const allowed = parseFloat(row.find('.comp option:selected').data('marks')) || 0;
                const input = row.find('.marks-field');
                const errorMsg = row.find('.validation-error');

                const totalForComponent = componentTotals[compId]?.total || 0;

                if (totalForComponent > allowed) {
                    errorMsg.show();
                    input.addClass('is-invalid');
                } else {
                    errorMsg.hide();
                    input.removeClass('is-invalid');
                }
            });
        }

        // Re-validate on marks change
        $(document).on('input', '.marks-field', function () {
            validateAllMarks();
        });

        // Trigger initial change
        $('.comp').trigger('change');
    });
</script>
