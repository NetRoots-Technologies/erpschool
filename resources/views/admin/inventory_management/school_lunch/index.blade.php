@extends('admin.layouts.main')

@section('title')
    Student Meal
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
                            <label for="class" class="form-label">Select Class</label>
                            <select name="class" id="class" class="form-select" required>
                                <option value="" selected disabled></option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="class" class="form-label">Select Section</label>
                            <select name="section" id="section" class="form-select" required>
                                <option value="" selected disabled></option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="date" class="form-label">Select Date</label>
                            <input type="date" id="date" name="date" min="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>

                        <div class="col-md-1">
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

                    <h2 class="form-label my-4">Assigning Meals to Students</h2>

                    <div class="row student_head">
                        <div class="col-3 fs-5 fw-bold">Student</div>
                        <div class="col-2 fs-5 fw-bold">Class</div>
                        <div class="col-2 fs-5 fw-bold">Section</div>
                        <div class="col-2 fs-5 fw-bold">Meal</div>
                        <div class="col-2 fs-5 fw-bold">Served</div>
                    </div>
                    <form action="{{ route('inventory.school_lunch.store') }}" id="student_form" method="post">
                        <input type="hidden" id="student_count" name="student_count" value="0">
                        <div id="student_lists"></div>
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
            let section = @json($section);
            let student_attendance = @json($student_attendance);
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
                        console.log("🚀 ~ response>>", response)
                        let products = response.data;
                        let productHtml = "";

                        products.forEach(product => {
                            productHtml += `<option value="${product.id}" data-name="${product.name}" data-quantity="${product.quantity}">
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

            $(`#student_form`).validate({
                ignore: [],
                rules: {
                    student_section: "required"
                },
                messages: {
                    student_section: "Please Select Section"
                },
                submitHandler: function(form) {
                    let allValid = true;

                    $(".student_lunch").each(function() {
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
                        $("#batch_type").addClass("is-invalid");
                        allValid = false;
                    } else {
                        $("#batch_type").removeClass("is-invalid");
                    }


                    if (!allValid) return false;

                    let formData = $(form).serializeArray();
                    formData.push({
                        name: "branch",
                        value: $("#branch").val()
                    });
                    formData.push({
                        name: "class",
                        value: $("#class").val()
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
                                $("#student_form")[0].reset();
                                $(".formBtn").addClass("d-none");
                                $("#branch, #class, #finished_goods, #batch_type").val("")
                                    .trigger("change");
                                $("#student_lists").empty();
                                getProducts()

                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            const response = xhr?.responseJSON;

                            if (response?.errors) {
                                Object.entries(response.errors).forEach(([field,
                                    messages
                                ]) => {
                                    if (Array.isArray(messages)) {
                                        messages.forEach(message => toastr.error(
                                            message));
                                    } else {
                                        toastr.error(messages);
                                    }
                                });
                            } else if (response?.message) {
                                toastr.error(response.message);
                            } else {
                                toastr.error(
                                    `Unexpected error: ${error || 'Unknown error occurred.'}`
                                );
                            }
                        }
                    });
                },

            })

            $("#campus, #branch, #class, #section, #finished_goods, #batch_type").select2({
                placeholder: "Select an option"
            });

            branches.forEach(element => {
                $('#branch').append(`<option value="${element.id}">${element.name}</option>`);
            });

            $('#branch').on('change', function() {
                let branchId = $(this).val();
                $('#class').empty().append('<option value="">Select Class</option>');
                $('#section').empty().append('<option value="">Select Section</option>');
                $("#student_lists").empty();
                $(".formBtn").addClass("d-none");
                $(".error").remove();
                $("#batch_type").val("").trigger("change");
                $("#finished_goods").val("").trigger("change");
                let selectedBranch = branches.find(branch => branch.id == branchId);

                if (selectedBranch && selectedBranch.classes.length) {
                    selectedBranch.classes.forEach(cls => {
                        $('#class').append(`<option value="${cls.id}">${cls.name}</option>`);
                    });
                }
            });

            $('#class').on('change', function(e) {
                e.preventDefault();
                let classId = $(this).val();

                $('#section').empty().append('<option value=""></option>');

                let selectedSections = section.filter(cls => cls.class_id == classId);

                if (selectedSections.length) {
                    selectedSections.forEach(sec => {
                        $('#section').append(`<option value="${sec.id}">${sec.name}</option>`);
                    });
                }
            });

            $("#finished_goods").on('change', function(e) {
                var selectedId = $(this).val();
                var selectedText = $(this).find("option:selected").text().trim();

                var cleanedText = selectedText.replace(/\s*\(\d+\)\s*$/, "").trim();

                var quantity = selectedText.match(/\((\d+)\)/);
                var count = $("#student_lists .student_id").length;

                if (selectedId === lastSelectedId) {
                    $(this).val("");
                    $(".student_lunch_id").val("");
                    $(".student_lunch").val("");
                    lastSelectedId = null;
                    return;
                }

                if (quantity) {
                    var product_quantity = parseInt(quantity[1], 10);
                    if (product_quantity < count) {
                        toastr.error("Not enough products to serve lunch student.");
                        $(".student_lunch_id").val("");
                        $(".student_lunch").val("");
                        lastSelectedId = null;
                        return false;
                    }
                }

                $(".student_lunch_id").val(selectedId);
                $(".student_lunch").val(cleanedText);
                lastSelectedId = selectedId;
                updateCount();
            });


            $("#searchButton").on("click", function (e) {
                $("#student_lists").empty();

                var selectedClassId = $("#class option:selected").val();
                var selectedClassText = $("#class option:selected").text();
                var selectedSectionId = $("#section option:selected").val();
                var selectedSectionText = $("#section option:selected").text();

                let filtered_students = student_attendance
                    .flatMap(stu => stu.attendance_data)
                    .filter(attendance => {
                        return attendance.student?.class_id == selectedClassId &&
                            (selectedSectionId === "" || attendance.student?.section_id == selectedSectionId);
                    })
                    .map(attendance => attendance.student);

                filtered_students.forEach((stu, i) => {
                    // Find the correct section name based on the student's section_id
                    let studentSectionText = selectedSectionId
                        ? selectedSectionText
                        : (section.find(sec => sec.id == stu.section_id)?.name || "Not Assigned");

                    let html = `
                        <div class="row p-1">
                            <input type="hidden" name="student_id[]" class="student_id" value="${stu.id}">

                            <div class="col-3">
                                <input type="text" name="student_name[]" class="form-select student_name border-0 p-0"
                                    value="${stu.first_name} ${stu.father_name}" style="color:black;" readonly>
                            </div>

                            <div class="col-2">
                                <input type="hidden" name="student_class_id" class="student_class_id" value="${stu.class_id}">
                                <input type="text" name="student_class" class="form-select student_class border-0 p-0" value="${selectedClassText}" readonly>
                            </div>

                            <div class="col-2">
                                <input type="hidden" name="student_section_id[${i}]" class="student_section_id" value="${stu.section_id}">
                                <input type="text" name="student_section[${i}]" class="form-select student_section border-0 p-0" value="${studentSectionText}" readonly>
                            </div>

                            <div class="col-2 p-0">
                                <input type="hidden" name="student_lunch_id" class="student_lunch_id" value="">
                                <input type="text" name="student_lunch" class="form-select student_lunch border-0 p-0" value="" readonly>
                            </div>

                            <div class="col-2">
                                <input type="hidden" name="assigned[${i}]" value="0">
                                <input type="checkbox" class="assigned" name="assigned[${i}]" value="1" checked>
                            </div>
                        </div>`;

                    $("#student_lists").append(html);
                    $(".formBtn").removeClass("d-none");
                });

                $("#finished_goods").val("").trigger("change");
            });

            function updateCount() {
                var count = $("#student_lists .student_id").length;
                $("#student_count").val(count);
            }
            getProducts();

        })
    </script>
@endsection
