@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Users Management</h2>
    <div>
        <a href="{{ route('users.export') }}" class="btn btn-success me-2">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa-solid fa-plus"></i> Add New User
        </button>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4 border-0 p-3">
    <form action="{{ route('users.index') }}" method="GET" id="filterForm">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label text-muted small">Name</label>
                <input type="text" name="name" class="form-control bg-dark text-white border-secondary auto-filter" placeholder="Search name..." value="{{ request('name') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small">Role</label>
                <select name="role" class="form-select bg-dark text-white border-secondary auto-filter">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                </select>
            </div>
            <div class="col-md-1">
                <a href="{{ route('users.index') }}" class="btn btn-secondary w-100" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </div>
    </form>
</div>

@if(session('generated_password'))
    <div class="alert alert-info border-0 shadow-sm fw-bold">
        <i class="fa-solid fa-key"></i> {{ session('generated_password') }}
    </div>
@endif

<div class="table-responsive">
    <table class="table table-striped table-hover mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $u)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td><span class="badge bg-{{ $u->role == 'admin' ? 'danger' : 'info' }}">{{ strtoupper($u->role) }}</span></td>
                <td>
                    <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-warning mb-1"><i class="fa-solid fa-pen"></i> Edit</a>
                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" id="delete-form-{{ $u->id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger mb-1" onclick="confirmDelete('delete-form-{{ $u->id }}')"><i class="fa-solid fa-trash"></i> Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New System User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-4">
                    <i class="fa-solid fa-info-circle"></i> Password belongs to rule: First 4 characters of Name or Email + Database ID Number. It will be shown after successful creation.
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label>Role</label>
                            <select name="role" class="form-select select2-user" required>
                                <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.select2-user').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#addUserModal')
        });

        // Auto Filtering Logic
        let filterTimer;
        $('.auto-filter').on('input change', function() {
            clearTimeout(filterTimer);
            let isSelect = $(this).is('select');
            
            filterTimer = setTimeout(function() {
                $('#filterForm').submit();
            }, isSelect ? 0 : 500);
        });

        @if($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            myModal.show();
        @endif
    });
</script>
@endsection
