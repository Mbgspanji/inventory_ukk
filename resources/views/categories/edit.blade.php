@extends('layouts.app')
@section('title', 'Edit Category')
@section('content')
<div class="card">
    <div class="card-body p-4">
        <h4 class="mb-4">Edit Category</h4>
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label">Division</label>
                <select name="division" class="form-select select2 @error('division') is-invalid @enderror">
                    <option value="Sarpas" {{ old('division', $category->division) == 'Sarpas' ? 'selected' : '' }}>Sarpas</option>
                    <option value="Tata Usaha" {{ old('division', $category->division) == 'Tata Usaha' ? 'selected' : '' }}>Tata Usaha</option>
                    <option value="Tefa" {{ old('division', $category->division) == 'Tefa' ? 'selected' : '' }}>Tefa</option>
                </select>
                @error('division')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <button class="btn btn-warning fw-bold" type="submit">Update Category</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
