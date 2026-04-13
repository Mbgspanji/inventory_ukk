@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h2 class="mb-4">Dashboard Overview</h2>
<div class="row g-4">
    <div class="col-md-3">
        <div class="card border-0 text-white" style="background-color: var(--primary-navy);">
            <div class="card-body d-flex align-items-center">
                <div class="display-4 me-3"><i class="fa-solid fa-tags"></i></div>
                <div>
                    <h3 class="mb-0">{{ $stats['categories'] }}</h3>
                    <div class="text-white-50">Categories</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 text-white" style="background-color: #0E2954;">
            <div class="card-body d-flex align-items-center">
                <div class="display-4 me-3"><i class="fa-solid fa-box"></i></div>
                <div>
                    <h3 class="mb-0">{{ number_format($stats['items']) }}</h3>
                    <div class="text-white-50">Total Items</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 text-white" style="background-color: #2E4F4F;">
            <div class="card-body d-flex align-items-center">
                <div class="display-4 me-3"><i class="fa-solid fa-handshake"></i></div>
                <div>
                    <h3 class="mb-0">{{ $stats['lendings'] }}</h3>
                    <div class="text-white-50">Active Lendings</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 text-white" style="background-color: #112240;">
            <div class="card-body d-flex align-items-center">
                <div class="display-4 me-3"><i class="fa-solid fa-users"></i></div>
                <div>
                    <h3 class="mb-0">{{ $stats['users'] }}</h3>
                    <div class="text-white-50">Users</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-5">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header py-3">
                <h5 class="mb-0 fw-bold">Total Items</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Name</th>
                                <th>Category</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allItems as $item)
                            <tr>
                                <td class="ps-3 fw-medium">{{ $item->name }}</td>
                                <td><span class="badge bg-secondary opacity-75">{{ $item->category->name }}</span></td>
                                <td class="text-center fw-bold">{{ $item->total }}</td>
                            </tr>
                            @endforeach
                            @if($allItems->isEmpty())
                                <tr><td colspan="3" class="text-center py-4 text-muted">No items available.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header py-3">
                <h5 class="mb-0 fw-bold">Items Repair</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3">Name</th>
                                <th class="text-danger text-center">Broke Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($repairItems as $item)
                            <tr>
                                <td class="ps-3 fw-medium">{{ $item->name }}</td>
                                <td class="text-center fw-bold text-danger">{{ $item->repair }}</td>
                            </tr>
                            @endforeach
                            @if($repairItems->isEmpty())
                                <tr><td colspan="2" class="text-center py-4 text-muted">No items in repair.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
