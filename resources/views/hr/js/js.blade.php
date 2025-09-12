<script>
    $(document).ready(function () {
        $('#selectBranch').on('change', function () {
            var branch_id = $(this).val();
            $.ajax({
                type: 'get',
                url: '{{route('hr.fetchDepartment')}}',
                data: {
                    branch_id: branch_id
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    var departmentDropdown = $('#selectDepartment').empty();

                    departmentDropdown.append('<option value="">Select Department</option>');

                    data.forEach(function (department) {
                        departmentDropdown.append('<option value="' + department.id + '">' + department.name + '</option>');
                    });

                },

            });
        });
        $('#selectDepartment').on('change', function () {
            var department_id = $(this).val();
            $.ajax({
                type: 'get',
                url: '{{route('hr.fetchEmployee')}}',
                data: {
                    department_id: department_id
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    var employeesDropdown = $('#selectEmployee').empty();

                    employeesDropdown.append('<option value="">Select Employee</option>');

                    data.forEach(function (employee) {
                        employeesDropdown.append('<option value="' + employee.id + '">' + employee.name + '</option>');
                    });
                },

            });
        });


    });
</script>
