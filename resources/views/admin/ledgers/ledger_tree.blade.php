@inject('request', 'Illuminate\Http\Request')
@inject('Currency', '\App\Helpers\Currency')

@extends('admin.layouts.main')
@section('title', 'Chart of Accounts')
@section('content')

    <style type="text/css">
        .accordion-button::after {
            background: none !important;
        }

        .accordion-button {
            padding: 0.5rem 1.25rem !important;
        }

        .menu .accordion-heading {
            position: relative;
            border-left: 4px solid #f38787;
        }

        .accordion-inner .nav>li {
            border-bottom: 1px solid #ecf1ec;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd !important;
            border: 1px solid #0d6efd !important;
            background-color: #eaf4ff;
            font-weight: bold;
        }

        .search-bar {
            margin-bottom: 15px;
        }

        #groupSearch {
            width: 100%;
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
        }

        #contextMenu {
            display: none;
            position: absolute;
            z-index: 10000;
            min-width: 150px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
        }

        #contextMenu ul {
            list-style: none;
            margin: 0;
            padding: 5px 0;
        }

        #contextMenu ul li {
            padding: 8px 15px;
            cursor: pointer;
        }

        #contextMenu ul li:hover {
            background-color: #f1f1f1;
        }

        #groupSearch {
            padding-right: 2.5rem;
        }

        .accordion-button {
            padding: 0.5rem 1.25rem !important;
            cursor: pointer;
        }

        .search-icon {
            font-size: 1.4rem;
        }

        .nav-tabs .nav-link {
            font-size: 1.1rem;
        }
    </style>

    <div class="container-fluid">
        <div class="card p-4">
            <div class="row">
                <div class="col-md-12">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-4" id="coaTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-accounts-tab" data-bs-toggle="tab" href="#all-accounts"
                                role="tab">All Accounts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="listing-tab" data-bs-toggle="tab" href="#listing" role="tab">Chart of
                                Account Listing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="import-tab" data-bs-toggle="tab" href="#import" role="tab">Import from
                                Excel</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- All Accounts Tab -->
                        <div class="tab-pane fade show active" id="all-accounts" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-md-6 position-relative">
                                    <input type="text" id="groupSearch" class="form-control form-control-lg pe-5"
                                        placeholder="Search by group name or number...">
                                    <span class="position-absolute top-50 end-0 translate-middle-y me-3">
                                        <i class="fas fa-search text-muted" style="font-size: 1.4rem;"></i>
                                    </span>

                                </div>
                            </div>
                            <div class="accordion" id="accordionGroup">
                                @foreach($Groups2 as $group)
                                    <div class="accordion-item">
                                        <div class="accordion-heading area">
                                            <a class="accordion-button" aria-expanded="true" data-bs-toggle="collapse"
                                                data-bs-target="#{{$group->code}}"
                                                onclick="get_group_ledger(this, {{$group->id}}, {{$group->level}});"
                                                data-id="{{$group->id}}" data-value="{{$group->code}}"
                                                data-level="{{$group->level}}">
                                                {{$group->code}} - {{$group->name}}
                                            </a>
                                        </div>
                                        <div class="accordion-body collapse" id="{{trim($group->code)}}">
                                            <div class="accordion-inner">
                                                <div class="accordion" id="equipamento1">
                                                    <!-- Loaded via AJAX -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Chart of Account Listing Tab -->
                        {{-- <div class="tab-pane fade" id="listing" role="tabpanel">
                            <div class="card p-4">
                                <div class="row w-100 mt-4 mb-4">
                                    <h3 class="text-22 text-center text-bold w-100 mb-4">
                                        <a href="{{ route('admin.ledger_tree') }}" class="text-decoration-none text-dark">
                                            Chart of Accounts
                                        </a>
                                    </h3>

                                    <div class="d-flex mb-3" style="padding-left: 35px;">
                                        <button class="btn btn-primary" onclick="printTable()">Print</button>
                                        <a href="{{ route('admin.groups.create') }}" class="btn btn-primary ms-2">Add Chart
                                            of Accounts</a>
                                    </div>

                                </div>
                                <div class="table-responsive" id="printSection">
                                    <table class="table table-bordered permissions-table">
                                        <thead>
                                            <tr>
                                                <th>Level</th>
                                                <th>Account Number</th>
                                                <th>Name</th>
                                                <th width="100px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($Grouping) > 0)
                                            @foreach ($Grouping as $id => $Group)
                                            <tr data-entry-id="{{ $id }}">
                                                <th>{!! $Group['level'] !!}</th>
                                                <td>
                                                    {!!$Group['number']!!}
                                                </td>
                                                <td>
                                                    {!!$Group['name']!!}
                                                </td>
                                                <td>

                                                    @if (!in_array($id, Config::get('constants.accounts_main_heads')))
                                                    <a href="{{ route('admin.groups.edit', [$id]) }}"
                                                        class="ml-2 btn mb-1 btn-primary"><i class="fa fa-pencil-square"
                                                            aria-hidden="true"></i></a>

                                                    {!! Form::open(array(
                                                    'class' => '',
                                                    'method' => 'DELETE',
                                                    'onsubmit' => "return confirm('" . 'Are you sure you want to Delete
                                                    this?' . ".');",
                                                    'route' => array('admin.groups.destroy', $id)
                                                    )) !!}
                                                    {{ csrf_field() }}
                                                    {!! Form::close() !!}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="5">No data found</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> --}}

                        <div class="tab-pane fade" id="listing" role="tabpanel">
                            <div class="table-responsive">
                                <button class="btn btn-primary mb-3 d-block ms-auto" onclick="addGroup()">Add New</button>
                                <table id="file-datatable"
                                    class="border-top-0 table table-bordered text-nowrap key-buttons text-center border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Code</th>
                                            <th class="heading_style">Name</th>
                                            <th class="heading_style">Detail Type</th>
                                            <th class="heading_style">Type</th>
                                            <th class="heading_style">Status</th>
                                            <th class="heading_style">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="switchCheckDefault">
                                </div>
                            </div>
                        </div>

                        <!-- Import from Excel Tab -->
                        <div class="tab-pane fade" id="import" role="tabpanel">
                            <div class="card p-4">
                                <h4>Import from Excel</h4>
                                <p>This is a placeholder for file upload form or import logic.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Context Menu -->
        <div id="contextMenu">
            <ul class="mb-0">
                <li onclick="addGroup()">Add</li>
                <li onclick="editGroup()">Edit</li>
                <li onclick="deleteGroup()">
                    Delete
                </li>
            </ul>
        </div>


        <!-- Add/Edit Group Modal -->
        <!-- Modal -->
        <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.groups.store') }}" id="groupForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="groupModalLabel">Add/Edit Group</h5>
                        </div>
                        <div class="modal-body">
                            @include('admin.ledgers.coa_fields') {{-- This should contain the input fields --}}
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <input type="hidden" id="get_ledger_tree" value="{{ route('admin.get_ledger_tree') }}">
    </div>

@endsection

@section('js')
<script>
    let selectedGroupId = null;
    let selectedGroupText = '';

    function get_group_ledger(obj, id, level) {
        const DID = $(obj).attr('data-value');
        const container = $(obj).closest('.accordion-item').find('.accordion-body');
        const get_ledger_tree = $('#get_ledger_tree').val();

        $.ajax({
            url: `${get_ledger_tree}/${id}`,
            dataType: 'JSON',
            success: function (data) {
                container.addClass('in').css('margin-left', '30px');
                $("#" + DID).html(data.data).toggleClass('good');
                color_level(DID, level);
                $("#" + DID).toggleClass("collapse");
            }
        });
    }

    function color_level(id, level) {
        const colors = ['#65c465', '#98b3fa', '#a83266', '#e0d7d7', '#141706'];
        const texts = ['text-success', 'text-info', 'text-warning', 'text-danger', 'text-secondary'];
        $("#" + id).find('.accordion-group').css('border-left', '4px solid ' + colors[level - 1]);
        $("#" + id).find('.accordion-toggle').addClass(texts[level - 1]);
    }

    document.getElementById("groupSearch").addEventListener("keyup", function () {
        const search = this.value.toLowerCase();
        // Filter accordion items
        document.querySelectorAll(".accordion-item").forEach(item => {
            const heading = item.querySelector(".accordion-button");
            const bodyContent = item.querySelector(".accordion-body");
            const bodyText = bodyContent ? bodyContent.innerText.toLowerCase() : '';
            const groupText = heading ? heading.textContent.toLowerCase() : '';

            // Show the item if search term is in heading or body
            if (groupText.includes(search) || bodyText.includes(search)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });

        // Filter ledger table rows
        document.querySelectorAll("#listing table tbody tr").forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(search)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });


    // Context Menu Logic
    document.addEventListener("contextmenu", function (e) {
        const button = e.target.closest(".accordion-button");

        if (button) {
            e.preventDefault();

            selectedGroupId = button.getAttribute("data-id");
            selectedGroupText = button.textContent.trim();

            const levelAttr = button.getAttribute("data-level");
            const level = parseInt(levelAttr || '0'); // Default to 0 if not present

            const menu = document.getElementById("contextMenu");

            // Show/hide menu items
            const addLi = menu.querySelector("li[onclick='addGroup()']");
            const editLi = menu.querySelector("li[onclick='editGroup()']");
            const deleteLi = menu.querySelector("li[onclick='deleteGroup()']");

            if (level >= 4) {
                addLi.style.display = 'none'; // Hide "Add" for level 3+
            } else {
                addLi.style.display = 'block'; // Show for level 1 & 2
            }

            editLi.style.display = 'block';
            deleteLi.style.display = 'block';

            menu.style.top = `${e.pageY}px`;
            menu.style.left = `${e.pageX}px`;
            menu.style.display = "block";

        } else {
            document.getElementById("contextMenu").style.display = "none";
        }
    });

    document.addEventListener("click", function () {
        document.getElementById("contextMenu").style.display = "none";
    });



    function editGroup(id = null) {
        console.log("Selected Group ID", selectedGroupId);
        groupId = selectedGroupId ?? id;
        if (groupId) {
            const fetchUrl = `{{ url('admin/fetch_coa') }}/${groupId}`;

            $.ajax({
                url: fetchUrl,
                type: 'GET',
                success: function (response) {
                    const data = response.result[0]; // Make sure to access the first object
                    console.log("Response Data:", data);

                    $('#groupForm')[0].reset();
                    $('#groupForm').attr('action', `/admin/groups/${groupId}`);
                    $('#groupModalLabel').text(`Edit COA (Group: ${data.name})`);

                    $('#name').val(data.name);
                    if (data.ledgers != null && data.ledgers.opening_balance) {
                        $('#balance').val(data.ledgers.opening_balance);
                    }
                    $('#parent_id').val(data.parent_id).trigger('change');
                    if (!$('#groupForm input[name="_method"]').length) {
                        $('#groupForm').append('<input type="hidden" name="_method" value="PUT">');
                    }
                    $('#groupModal').modal('show');
                },
                error: function (xhr) {
                    console.error("Fetch failed", xhr);
                    alert("Failed to fetch group details.");
                }
            });
        }
    }


    // function deleteGroup() {
    //     // alert($(this).closest('form').attr('action'));
    //     if (confirm(`Are you sure you want to delete group ${selectedGroupId}?`)) {
    //         alert(`Deleted Group: ${selectedGroupId}`);
    //     }
    // }
    function deleteGroup(id = null) {
        const groupId = selectedGroupId ?? id;
        const deleteUrl = `/admin/groups/${groupId}`;

        if (confirm(`Are you sure you want to delete group ${groupId}?`)) {
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    toastr.success(response.message || 'Group deleted successfully.');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function (xhr) {
                    let errorMessage = 'Failed to delete the group.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    toastr.error(errorMessage);
                    console.error(xhr.responseText);
                }
            });
        }
    }

    function addGroup() {
        $('#groupForm')[0].reset();
        $('#parent_id').val(selectedGroupId).trigger('change');
        //$('#groupModalLabel').text(`Add New COA (Chart of Account) (Parent: ${selectedGroupText})`);
        $('#groupModalLabel').text(`Add New COA (Chart of Account)`);
        $('#groupModal').modal('show');
    }

    document.querySelector('.btn-secondary').addEventListener('click', function () {
        const modal = bootstrap.Modal.getInstance(document.getElementById('groupModal'));
        modal.hide();
    });

    $(document).ready(function () {
        $('.select2').select2();
        $(document).on('click', '.edit-btn', function () {
            editGroup($(this).data('id'));
        });

        $(document).on('change', '#status-switch', function () {
            const status = $(this).is(':checked') ? 1 : 0;
            const id = $(this).data('id');
            $.ajax({
                url: `/admin/coa/${id}/toggle-status`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',  // For Laravel CSRF protection
                    status: status
                },
                success: function (response) {
                    Swal.fire('Success', response.message, 'success');
                },
                error: function (xhr) {
                    Swal.fire('Error', 'Something went wrong!', 'error');
                }
            });


        });
        $(document).on('submit', '.delete_form', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this group?')) {
                this.submit(); // Manually submit if confirmed
            }
        });
    });


    //data tables
    var tableData = $('#file-datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            },
            'colvis'
        ],
        ajax: {
            url: "{{ route('admin.listing') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}" }
        },
        columns: [
            { data: 'code', name: 'code', },
            { data: 'name', name: 'name' },
            { data: 'detail_type', name: 'company_name' },
            { data: 'type', name: 'type' },
            { data: 'status', name: 'state', orderable: true },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        columnDefs: [
            { "visible": false }
        ]
    });



</script>
<script>
    function printTable() {
        var printContent = document.getElementById("printSection").innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        //location.reload();
    }
</script>
@stop