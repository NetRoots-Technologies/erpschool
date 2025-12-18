@extends('admin.layouts.main')

@section('title')
    Add Signatory Authority
@stop

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Define Signatory Authority</h3>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

           <form action="{{ route('admin.signatory-authorities.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="module">Module Name <span class="text-danger">*</span></label>
                    <select name="module" class="form-control" required>
                        <option value="">-- Select Module --</option>
                        <option value="leave">Leave</option>
                        <option value="academic">Academic</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="company_id">Company <span class="text-danger">*</span></label>
                    <select name="company_id" id="company_id" class="form-control" required>
                        <option value="">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="branch_id">Branch</label>
                    <select name="branch_id" id="branch_id" class="form-control">
                        <option value="">Select Branch</option>
                    </select>
                </div>
                 {{-- <div class="form-group">
                    <label for="role_id">Approval Role</label>
                    <select name="approval_role_id" class="form-control">
                        <option value="">Select Role (optional)</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }} (Level {{ $role->level }})</option>
                        @endforeach
                    </select>
                </div> --}}

               <div class="form-group">
                <label>Approval Role</label>
                <select name="approval_role_id" class="form-control" required>
                    <option value="">Select Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">
                            {{ $role->name }}
                        </option>

                    @endforeach
                </select>
            </div>


                <div class="form-group">
                    <label for="user_id">Specific User</label>
                    <select name="user_id" class="form-control">
                        <option value="">Select User (optional)</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

        

                <div class="form-group">
                    <label for="is_active">Active</label>
                    <select name="is_active" class="form-control">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Save Authority</button>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')


<script>
    $(document).ready(function(){
        $('#company_id').on('change', function () {
            let companyId = $(this).val();
            $('#branch_id').html('<option value="">Loading...</option>');

            if (companyId) {
                $.ajax({
                    url: '{{ route('hr.fetch.branches') }}',
                    type: 'GET',
                    data: { companyid: companyId },
                    success: function (data) {
                        $('#branch_id').empty().append('<option value="">Select Branch</option>');
                        $.each(data, function (key, branch) {
                            $('#branch_id').append('<option value="' + branch.id + '">' + branch.name + '</option>');
                        });
                    },
                    error: function () {
                        $('#branch_id').html('<option value="">Failed to load</option>');
                    }
                });
            } else {
                $('#branch_id').html('<option value="">Select Branch</option>');
            }
        });
    });
</script>
@endsection
