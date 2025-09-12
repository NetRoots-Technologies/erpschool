<table id="users-table" class="table radio_table table-bordered table-striped datatable mt-3" style="width: 100%">
    <thead>
        <tr>
            <th>Sr.#</th>
            <th>Student Name</th>
            <th class="header-radio">
                <div class="form-check form-check-inline">
                    <input class="form-check-input present-radio radio_button_style" id="check-all-present"
                        name="check-all" type="radio">
                    <label class="form-check-label" for="check-all-present">Present</label>
                </div>
            </th>

            <th class="header-radio">
                <div class="form-check form-check-inline">
                    <input class="form-check-input absent-radio radio_button_style" id="check-all-absent"
                        name="check-all" type="radio">
                    <label class="form-check-label" for="check-all-absent">Absent</label>
                </div>
            </th>
            <th class="header-radio">
                <div class="form-check form-check-inline">
                    {{-- <input class="form-check-input leave-radio radio_button_style" id="check-all-leave"
                        name="check-all" type="radio"> --}}
                    <label class="form-check-label" for="check-all-leave">Leave</label>
                </div>
            </th>

        </tr>
    </thead>
    <tbody>
        @foreach($students as $student)
        <tr>
            <td class="col-2">{!! $loop->iteration !!}</td>
            <td class="col-4">
                <div class="d-flex align-items-center">
                    <input type="hidden" name="student_id[]" value="{!! $student->id !!}">
                    <input type="text" class="form-control" name="student_name[]"
                        value="{!!$student->student_id.' - '.$student->first_name.' '.$student->last_name !!}" readonly>
                </div>
            </td>
            <td class="col-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input present-radio" type="radio" name="attendance[{!! $student->id !!}]"
                        id="present_{!! $student->id !!}" value="P" data-id="{!! $student->id !!}">
                    <label class="form-check-label" for="present_{!! $student->id !!}">Present</label>
                </div>
            </td>

            <td class="col-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input absent-radio" type="radio" name="attendance[{!! $student->id !!}]"
                        id="absent_{!! $student->id !!}" value="A" data-id="{!! $student->id !!}">
                    <label class="form-check-label" for="absent_{!! $student->id !!}">Absent</label>
                </div>
            </td>
            <td class="col-2">
                <div class="form-group">
                    <select class="form-control leave_attendence" name="attendance[{!! $student->id !!}]"
                        id="leave_{!! $student->id !!}" data-id="{!! $student->id !!}">
                        <option value="" disabled selected>Select Leave Type</option>
                        <option value="Death">Death</option>
                        <option value="Discipline">Discipline</option>
                        <option value="Event Leave">Event Leave</option>
                        <option value="Long Leave">Long Leave</option>
                        <option value="Official">Official</option>
                        <option value="Other">Other</option>
                        <option value="Outstation">Outstation</option>
                        <option value="Prep Leave">Prep Leave</option>
                        <option value="Sick">Sick</option>
                        <option value="Wedding">Wedding</option>
                    </select>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    try {


    document.getElementById('check-all-present').addEventListener('click', function () {
        var presentRadios = document.getElementsByClassName('present-radio');

        for (var i = 0; i < presentRadios.length; i++) {
            presentRadios[i].checked = true;
        }
    });

    // $(`#form_validation`).on('change','.leave_attendence',function(e) {
    //     e.preventDefault()
    //     let id = $(this).data('id')
    //     var selectedSelectName = $(`#leave_${id}`).attr('name');
    //     $(`input[name="${selectedSelectName}"]`).prop('checked', false);
    // });

    $(document).ready(function () {

    $(".present-radio, .absent-radio").on("click", function () {
        const studentId = $(this).data("id");
        $(`#leave_${studentId}`).val("");
    });

    $(".leave_attendence").on("change", function () {
        const studentId = $(this).data("id");
        $(`#present_${studentId}`).prop("checked", false);
        $(`#absent_${studentId}`).prop("checked", false);
        $(`#leave_${studentId}`).prop("disabled", false);
    });

});


    $('#users-table tr td').on('change', function () {
        // var id = $(this).find('input[type="radio"]').data('id');
        $(this).find('input[type="radio"]:checked').val();

    });
} catch (ee){
    console.log("🚀 ~ ee>>", ee)

    }
</script>
