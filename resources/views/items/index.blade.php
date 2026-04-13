@extends('layouts.app')
@section('title', 'Items')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-box"></i> Items Dashboard</h2>
    <div>
        <a href="{{ route('items.export') }}" class="btn btn-success me-2">
            <i class="fa-solid fa-file-excel"></i> Export Excel
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
            <i class="fa-solid fa-plus"></i> Add Item
        </button>
    </div>
</div>


<!-- Filter Card -->
<div class="card mb-4 border-0 p-3">
    <form action="{{ route('items.index') }}" method="GET" id="filterForm">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label text-muted small">Category</label>
                <select name="category_id" class="form-select bg-dark text-white border-secondary select2-filter auto-filter">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->division }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small">Item Name</label>
                <input type="text" name="name" class="form-control bg-dark text-white border-secondary auto-filter" placeholder="Search item name..." value="{{ request('name') }}">
            </div>
            <div class="col-md-1">
                <a href="{{ route('items.index') }}" class="btn btn-secondary w-100" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </div>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Category</th>
                <th>Name</th>
                <th>Total</th>
                <th>Available</th>
                <th>Lending Total</th>
                <th>Repair</th>
                <th width="200">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><span class="badge bg-secondary">{{ $item->category->name }}</span></td>
                <td class="fw-bold">
                    <a href="{{ route('items.history', $item->id) }}" class="text-decoration-none">{{ $item->name }}</a>
                </td>
                <td>{{ $item->total }}</td>
                <td><span class="text-success fw-bold">{{ $item->available }}</span></td>
                <td><a href="{{ route('lendings.index', ['item_id' => $item->id]) }}" class="text-warning fw-bold text-decoration-none">{{ $item->lending_total }}</a></td>
                <td><span class="text-danger fw-bold">{{ $item->repair }}</span></td>
                <td>
                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen"></i> Edit</a>
                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline" id="delete-form-{{ $item->id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $item->id }}')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Add Item -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('items.store') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Item Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Category</label>
                            <select name="category_id" class="form-select select2-modal @error('category_id') is-invalid @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->division }})
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label>Total Stock</label>
                            <input type="number" name="total" class="form-control @error('total') is-invalid @enderror" min="0" value="{{ old('total') }}">
                            @error('total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save Item</button>
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
        $('.select2-modal').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#addItemModal')
        });
        
        $('.select2-filter').select2({
            theme: 'bootstrap-5'
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
            var myModal = new bootstrap.Modal(document.getElementById('addItemModal'));
            myModal.show();
        @endif
    });
</script>
@endsection
