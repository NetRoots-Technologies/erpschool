<script>
    $(document).ready(function () {

        $('.companySelect').on('change', function () {
            var selectedCompanyId = $('.companySelect').val();

            $.ajax({
                type: 'GET',
                url: '{{ route('hr.fetch.branches') }}',
                data: {
                    companyid: selectedCompanyId
                },
                success: function (data) {
                    var branchesDropdown = $('.branch_select').empty();

                    branchesDropdown.append('<option value="">Select Branch</option>');

                    data.forEach(function (branch) {
                        branchesDropdown.append('<option value="' + branch.id + '">' + branch.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                }
            });
        }).change();


        $('.branch_select').on('change', function () {

            var branch_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetchClass') }}',
                data: {
                    branch_id: branch_id
                },
                success: function (data) {
                    var sectionDropdown = $('.select_class').empty();

                    sectionDropdown.append('<option value="">Select Class</option>');

                    data.forEach(function (academic_class) {
                        sectionDropdown.append('<option value="' + academic_class.id + '">' + academic_class.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                }
            });

        });
    })
</script>

<script>
    $(document).ready(function () {
        $('.select_class').on('change', function () {

            var class_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetchSections') }}',
                data: {
                    class_id: class_id
                },
                success: function (data) {
                    var sectionDropdown = $('.select_section').empty();

                    sectionDropdown.append('<option value="">Select Section</option>');

                    data.forEach(function (section) {
                        sectionDropdown.append('<option value="' + section.id + '">' + section.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                }
            });

        });
    })
</script>

<script>
    $(document).ready(function () {

        $('.companySelect').on('change', function () {
            var selectedCompanyId = $('.companySelect').val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetch.sessions') }}',
                data: {
                    companyid: selectedCompanyId
                },
                success: function (data) {
                    var sessionDropdown = $('.session_select').empty();

                    sessionDropdown.append('<option value="">Select Session</option>');

                    data.forEach(function (session) {
                        sessionDropdown.append('<option value="' + session.id + '">' + session.name + '</option>');
                    });
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                }
            });
        }).change();
    })

</script>

<script>
    $(document).ready(function () {

        $('.sibling_name').on('change', function () {
            var Student_id = $('.sibling_name').val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetch.siblingClass') }}',
                data: {
                    student_id: Student_id
                },
                success: function (data) {
                    var siblingClass = $('.sibling_class').empty();

                    siblingClass.append('<option value="">Select Class</option>');
                    siblingClass.append('<option value="' + data.id + '">' + data.name + '</option>');
                },
                error: function (error) {
                    console.error('Error fetching branches:', error);
                }
            });
        });
    })

</script>


<script>
    $(document).ready(function () {
        $('.sibling_name').on('change', function () {
            var studentId = $(this).val();
            var siblingDOBField = $(this).closest('.row').find('.dob-field');
            var siblingGenderField = $(this).closest('.row').find('.gender-field');

            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetch_siblingDob') }}',
                data: {
                    student_id: studentId
                },
                success: function (data) {
                    siblingDOBField.val(data.student_dob);
                    siblingGenderField.val(data.gender).trigger('change');
                },
                error: function (error) {
                    console.error('Error fetching sibling data:', error);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('.branch_select').on('change', function () {
            var branch_id = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetch_studentId') }}',
                data: {
                    branch_id: branch_id
                },
                success: function (data) {
                    if (data.new_student_id) {
                        $('.student-id').val(data.new_student_id);
                    } else {
                        console.error('Error: new student ID not returned.');
                    }
                },
                error: function (error) {
                    if (error.status === 404) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Branch not found. Please add a branch code.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('.student-id').val('');
                            }
                        });
                    } else {
                        console.error('Error fetching new student ID:', error);
                    }
                }

            });
        }).change();
    });


</script>

<script>
    $(document).ready(function () {
        $('.select_section').on('change', function () {
            var section = $(this).val();
            var branch_id = $('.branch_select').val();
            var class_id = $('.select_class').val();
            $.ajax({
                type: 'GET',
                url: '{{ route('academic.fetchRollNo') }}',
                data: {
                    section: section,
                    branch_id: branch_id,
                    class_id: class_id,
                },
                success: function (data) {
                    if (data.studentRollNo) {
                        $('.student-rollNo').val(data.studentRollNo);
                    }
                },
                error: function (error) {
                    console.error('Error fetching sibling data:', error);
                }
            });
        });
    });

</script>


