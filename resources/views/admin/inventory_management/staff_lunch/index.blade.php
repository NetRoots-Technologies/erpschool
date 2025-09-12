@extends('admin.layouts.main')

@section('title')
    Staff Lunch
@stop

@section('content')


    <div class="container-fluid">
        <div class="row justify-content-center my-5">
            <div class="col-12">
                <div class="card basic-form shadow-sm p-5">

                    <div class="row my-1">

                        <div class="col-md-3">
                            <label for="branch" class="form-label">Select Branch</label>
                            <select name="branch" id="branch" class="form-select" required>
                                <option value="" selected disabled></option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="department" class="form-label">Select Department</label>
                            <select name="department" id="department" class="form-select" required>
                                <option value="" selected disabled></option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="date" class="form-label">Select Date</label>
                            <input type="date" id="date" name="date" min="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <div class="searchClass ms-4">
                                <button class="btn btn-primary mt-4" id="searchButton">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row my-4">
                        <div class="col-md-6">
                            <label for="finished_goods" class="form-label" required>Select Meal</label>
                            <select name="finished_goods" id="finished_goods" class="form-select">
                                <option value="" selected disabled></option>

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="batch_type" class="form-label">Meal Type</label>
                            <select name="batch_type" id="batch_type" class="form-select" required>
                                <option value="" selected disabled></option>
                                @foreach ($batch_types as $key => $value)
                                    <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h2 class="form-label my-4">Assigning Meals to Employees</h2>

                    <div class="row staff_head">
                        <div class="col-3 fs-5 fw-bold">Employee</div>
                        <div class="col-3 fs-5 fw-bold">Department</div>
                        <div class="col-3 fs-5 fw-bold">Meal</div>
                        <div class="col-3 fs-5 fw-bold">Served</div>
                    </div>

                    <form action="{{ route('inventory.staff_lunch.emp_store') }}" id="staff_form" method="post">
                        <input type="hidden" id="employee_count" name="employee_count" value="0">
                        <div id="staff_lists"></div>
                        <div class="d-flex justify-content-start mt-4">
                            <button type="submit" class="btn btn-primary formBtn d-none">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script defer>
        $(document).ready(function() {
            'use strict';
            var globalCount = 0;
            let branches = @json($branches);
            let department = @json($department);
            var lastSelectedId = null;
            const getProductsUri = @json(route('inventory.inventry.stationery.listing'));

            function getProducts()
            {
                $.ajax({
                url: getProductsUri,
                type: "GET",
                beforeSend: function(xhr){
                            var token = $('meta[name="csrf-token"]').attr('content');
                            xhr.setRequestHeader('X-CSRF-TOKEN', token);
                },
                success: function(response) {
                        let products = response.data;
                        let productHtml = "";

                        products.forEach(product => {
                            productHtml += `<option value="${product.id}" data-name="${product.name}" data-quantity="${product.quantity}" ${product.quantity == 0 ? "disabled" : ""}>
                                ${product.name} (${product.quantity})
                            </option>`;
                        });

                        $("#finished_goods").html(productHtml);
                },
                error: function(xhr, status, error) {
                    toastr.error("Error in getting product quantity: ", error);
                }
            });

            }

            $("#branch, #department, #finished_goods, #batch_type").select2({
                placeholder: "Select an option"
            });

            branches.forEach(element => {
                $('#branch').append(`<option value="${element.id}">${element.name}</option>`);
            });

            $('#branch').on('change', function() {
                let branchId = $(this).val();
                $('#department').empty().append('<option value="">Select department</option>');
                $("#staff_lists").empty();
                $(".formBtn").addClass("d-none");
                $(".error").remove();
                $("#batch_type").val("").trigger("change");
                $("#finished_goods").val("").trigger("change");

                let selectedBranch = branches.find(branch => branch.id == branchId);

                if (selectedBranch) {
                    selectedBranch.department.forEach(dept => {
                        $('#department').append(`<option value="${dept.id}">${dept.name}</option>`);
                    });
                }
            });

            $("#searchButton").on("click", function(e) {
                $("#staff_lists").empty();
                let employees = [];
                let departmentsInBranch = [];
                var selectedBranch = $("#branch option:selected").val();
                var selectedDept = $("#department option:selected").val();

                if (selectedBranch && selectedDept) {

                    const selectedDeptObj = department.find(dept => dept.id == selectedDept);
                    employees = selectedDeptObj.employee || [];

                }
                else {
                    departmentsInBranch = department.filter(dept => dept.branch_id == selectedBranch);
                    console.log(departmentsInBranch);

                    departmentsInBranch.forEach(dept => {
                        employees = employees.concat(dept.employee);
                    });
                }

                employees.forEach((emp, i) => {
                    let empDept = department.find(dept => dept.id == emp.department_id);
                    let departmentName = empDept ? empDept.name : "NA";

                    let html = `
                        <div class="row p-1">
                            <input type="hidden" name="employee_id[]" class="employee_id" value="${emp.id}">
                            <div class="col-3">
                                <input type="text" name="employee_name[]" class="form-select employee_name border-0 p-0" value="${emp.name}" style="color:black;" readonly>
                            </div>

                            <div class="col-3">
                                <input type="hidden" name="employee_department_id" class="employee_department_id" value="${emp.department_id}" >
                                <input type="text" name="employee_department_name" class="form-select employee_department_name border-0 p-0" value="${departmentName}" readonly>
                            </div>

                            <div class="col-3">
                                <input type="hidden" name="employee_lunch_id" class="employee_lunch_id" value="" >
                                <input type="text" name="employee_lunch" class="form-select employee_lunch border-0 p-0" value="" readonly>
                            </div>

                            <div class="col-3">
                                <input type="hidden" name="assigned[${i}]" value="0">
                                <input type="checkbox" class="assigned" name="assigned[${i}]" value="1" checked>
                            </div>

                        </div>`;

                    $("#staff_lists").append(html);
                });
                if(employees.length > 0){
                    $(".formBtn").removeClass("d-none");
                }else {
                    let html = `<div class="row p-1">
                        No Emplyee Found 
                    </div>`
                    $("#staff_lists").append(html);
                }

                $("#finished_goods").val("").trigger("change");
            });

            $("#finished_goods").on('change', function(e) {
                var selectedId = $(this).val();
                var selectedText = $(this).find("option:selected").text().trim();

                var cleanedText = selectedText.replace(/\s*\(\d+\)\s*$/, "").trim();

                var quantity = selectedText.match(/\((\d+)\)/);
                var count = $("#staff_lists .employee_id").length;

                if (selectedId === lastSelectedId) {
                    $(this).val("");
                    $(".employee_lunch_id").val("");
                    $(".employee_lunch").val("");
                    lastSelectedId = null;
                    return;
                }

                if (quantity) {
                    var product_quantity = parseInt(quantity[1], 10);
                    if (product_quantity < count) {
                        toastr.error("Not enough products to serve lunch employees.");
                        $(".employee_lunch_id").val("");
                        $(".employee_lunch").val("");
                        lastSelectedId = null;
                        return false;
                    }
                }

                $(".employee_lunch_id").val(selectedId);
                $(".employee_lunch").val(cleanedText);
                lastSelectedId = selectedId;
                updateCount();
            });


            $("#staff_form").validate({
                ignore: [],
                submitHandler: function(form) {
                    let allValid = true;

                    $(".employee_lunch").each(function() {
                        if ($(this).val().trim() === "") {
                            $(this).next(".error").remove();
                            $(this).after('<label class="error">Please Select Lunch.</label>');
                            allValid = false;
                        }
                    });

                    if ($("#date").val() === "" || $("#date").val() === null)
                    {
                        toastr.error("Please select a date.");
                        allValid = false;
                    }

                    if ($("#batch_type").val() === "" || $("#batch_type").val() === null) {
                        toastr.error("Please select a Meal Type.");
                        allValid = false;
                    }

                    if (!allValid) return false;

                    let formData = $(form).serializeArray();
                    formData.push({
                        name: "branch",
                        value: $("#branch").val()
                    });
                    formData.push({
                        name: "department",
                        value: $("#department").val()
                    });
                    formData.push({
                        name: "date",
                        value: $("#date").val()
                    });
                    formData.push({
                        name: "finished_goods",
                        value: $("#finished_goods").val()
                    });
                    formData.push({
                        name: "batch_type",
                        value: $("#batch_type").val()
                    });

                    $.ajax({
                        url: $(form).attr('action'),
                        type: $(form).attr('method'),
                        data: formData,
                        beforeSend: function(xhr) {
                            var token = $('meta[name="csrf-token"]').attr('content');
                            xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $("#staff_form")[0].reset();
                                $(".formBtn").addClass("d-none");
                                $("#branch, #department, #finished_goods, #batch_type").val("").trigger("change");
                                $("#staff_lists").empty();
                                getProducts()

                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            const response = xhr?.responseJSON;

                            if (response?.errors) {
                                Object.entries(response.errors).forEach(([field, messages]) => {
                                    if (Array.isArray(messages)) {
                                        messages.forEach(message => toastr.error(message));
                                    } else {
                                        toastr.error(messages);
                                    }
                                });
                            } else if (response?.message) {
                                toastr.error(response.message);
                            } else {
                                toastr.error(`Unexpected error: ${error || 'Unknown error occurred.'}`);
                            }
                        }
                    });
                }
            });

            function updateCount() {
                var count = $("#staff_lists .employee_id").length;
                $("#employee_count").val(count);
            }
            getProducts();
        })
    </script>
@endsection
