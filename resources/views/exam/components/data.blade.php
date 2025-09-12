<table id="users-table" class="table table-bordered table-striped datatable mt-3" style="width: 100%">
    <thead>
    <tr>
        <th>Sr.#</th>
        <th>Type</th>
        <th>Weightage (%)</th>
        <th>Total Marks</th>
    </tr>
    </thead>
    <tbody>
    @php($i=1)
    @foreach($types as $single)
        <tr>
            <td>{{$i++}}</td>
            <td>
                {{$single->name}}
                <input type="hidden" name="type_id[]" value="{{$single->id}}">
            </td>
            <td>
                <input type="number" class="form-control weightage-input" name="weightage[]" id="weightage-{{$single->id}}" required >
            </td>
            <td><input type="number" name="total_marks[]" class="form-control marks-input" id="marks-{{$single->id}}" required></td>
        </tr>
    @endforeach
    </tbody>
</table>
<div>
    Total Weightage: <span id="total-weightage">0</span>%
</div>
<div>
    Total Marks: <span id="total-marks">0</span>
</div>
<div id="error-message" style="color: red; display: none;">
    The total weightage cannot exceed 100%. The field causing the error has been reset to 0.
</div>
<div id="error-message2" style="color: red; display: none;">
    The total marks cannot exceed 100%. The field causing the error has been reset to 0.
</div>


<!-- <script>
    $(document).ready(function() {
        function updateTotalWeightage(input) {
            var total = 0;
            var exceeded = false;

            $('.weightage-input').each(function() {
                var value = parseFloat($(this).val()) || 0;
                total += value;
                if (total > 100 && !exceeded) {
                    exceeded = true;
                    $(input).val(0);
                    total -= value;
                }
            });

            $('#total-weightage').text(total);

            if (exceeded) {
                $('#error-message').show();
            } else {
                $('#error-message').hide();
            }
        }

        function updateTotalMarks(input) {
            var total = 0;
            var exceeded = false;

            $('.marks-input').each(function() {
                var value = parseFloat($(this).val()) || 0;
                total += value;
                if (total > 100 && !exceeded) {
                    exceeded = true;
                    $(input).val(0);
                    total -= value;
                }
            });

            $('#total-marks').text(total);

            if (exceeded) {
                $('#error-message2').show();
            } else {
                $('#error-message2').hide();
            }
        }

        $('.weightage-input').on('input', function() {
            updateTotalWeightage(this);
        });

        $('.marks-input').on('input', function() {
            updateTotalMarks(this);
        });

        updateTotalWeightage();
        updateTotalMarks();
    });
</script> -->


<script>
    $(document).ready(function() {
        function updateTotalWeightage(input) {
            var total = 0;
            var exceeded = false;

            $('.weightage-input').each(function() {
                var value = parseFloat($(this).val()) || 0;
                total += value;
                if (total > 100 && !exceeded) {
                    exceeded = true;
                    $(input).val(0);
                    total -= value;
                }
            });

            $('#total-weightage').text(total);

            if (exceeded) {
                $('#error-message').show();
            } else {
                $('#error-message').hide();
            }
        }

        function updateTotalMarks() {
            var total = 0;

            $('.marks-input').each(function() {
                var value = parseFloat($(this).val()) || 0;
                total += value;
            });

            $('#total-marks').text(total);
        }

        $('.weightage-input').on('input', function() {
            updateTotalWeightage(this);
        });

        $('.marks-input').on('input', function() {
            updateTotalMarks();
        });

        updateTotalWeightage();
        updateTotalMarks();
    });
</script>

