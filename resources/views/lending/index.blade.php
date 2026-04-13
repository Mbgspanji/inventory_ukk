@extends('layout.app')
@section('title', 'content')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-hand-holding-hand"></i> Lendings</h2>
        <div>
            <a href="{{ route('lendings.export') }}" class="btn btn-success me-2">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </a>
            @can('operator')
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLendingModal">
                    <i class="fa-solid fa-plus"></i> New Lending
                </button>
            @else
                <a href="{{ route('items.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
            @endcan
        </div>
    </div>
