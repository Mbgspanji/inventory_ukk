@extends('layouts.app')
@section('title', 'Categories')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-tags"></i> Categories</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fa-solid fa-plus"></i> Add Category
    </button>
</div>


<!-- Filter Card -->
<div class="card mb-4 border-0 p-3">
    <form action="{{ route('categories.index') }}" method="GET" id="filterForm">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label text-muted small">Category Name</label>
                <input type="text" name="name" class="form-control bg-dark text-white border-secondary auto-filter" placeholder="Search name..." value="{{ request('name') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small">Division</label>
                <select name="division" class="form-select bg-dark text-white border-secondary auto-filter">
                    <option value="">All Divisions</option>
                    <option value="Sarpas" {{ request('division') == 'Sarpas' ? 'selected' : '' }}>Sarpas</option>
                    <option value="Tata Usaha" {{ request('division') == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                    <option value="Tefa" {{ request('division') == 'Tefa' ? 'selected' : '' }}>Tefa</option>
                </select>
            </div>
            <div class="col-md-1">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary w-100" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </div>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Division</th>
                <th>Total Items Built</th>
                <th width="150">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $index => $c)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $c->name }}</td>
                <td>{{ $c->division }}</td>
                <td><span class="badge bg-secondary">{{ $c->items_count }} Items</span></td>
                <td>
                    <a href="{{ route('categories.edit', $c->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i></a>
                    <form action="{{ route('categories.destroy', $c->id) }}" method="POST" class="d-inline" id="delete-form-{{ $c->id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $c->id }}')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Add Category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Division</label>
                        <select name="division" class="form-select select2-cat @error('division') is-invalid @enderror">
                            <option value="">Select Division PJ</option>
                            <option value="Sarpas" {{ old('division') == 'Sarpas' ? 'selected' : '' }}>Sarpas</option>
                            <option value="Tata Usaha" {{ old('division') == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                            <option value="Tefa" {{ old('division') == 'Tefa' ? 'selected' : '' }}>Tefa</option>
                        </select>
                        @error('division')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save Category</button>
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
        $('.select2-cat').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#addCategoryModal')
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
            var myModal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
            myModal.show();
        @endif
    });
</script>
@endsection
