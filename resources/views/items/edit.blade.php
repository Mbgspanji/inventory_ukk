@extends('layouts.app')
@section('title', 'Edit Item')
@section('content')
<div class="card">
    <div class="card-body p-4">
        <h4 class="mb-4">Edit Item & Update Stock Condition</h4>
        <form action="{{ route('items.update', $item->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Item Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $item->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label>Category</label>
                    <select name="category_id" class="form-select select2 @error('category_id') is-invalid @enderror">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
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
                    <input type="number" name="total" class="form-control @error('total') is-invalid @enderror" min="0" value="{{ old('total', $item->total) }}">
                    @error('total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Total original stock. Incrementing this will add available stock.</small>
                </div>
                <div class="col-md-6">
                    <label>New Broke Item (Rusak Baru)</label>
                    <input type="number" name="new_broken" class="form-control" min="0" value="0">
                    <small class="text-danger">Akan mengurangi stok available dan menambah stok repair.</small>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-secondary">
                        <strong>Current Status:</strong> <br>
                        Available: {{ $item->available }} | Lending: {{ $item->lending_total }} | Repair: {{ $item->repair }}
                    </div>
                </div>
            </div>
            <button class="btn btn-warning fw-bold" type="submit">Update Item</button>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
