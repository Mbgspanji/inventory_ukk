@extends('layouts.app')
@section('title', 'Lending History')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fa-solid fa-clock-rotate-left"></i> History: {{ $item->name }}</h2>
    <a href="{{ route('items.index') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back to Items</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h5 class="card-title">Item Info</h5>
        <p class="mb-0">
            <strong>Category:</strong> {{ $item->category->name }} <br>
            <strong>Total Stock:</strong> {{ $item->total }} <br>
            <strong>Available:</strong> <span class="text-success">{{ $item->available }}</span> <br>
            <strong>In Lending:</strong> <span class="text-warning">{{ $item->lending_total }}</span> <br>
            <strong>Repairs:</strong> <span class="text-danger">{{ $item->repair }}</span>
        </p>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Borrower</th>
                <th>Amount</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Processed By</th>
                <th>Returned By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $index => $d)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $d->lending->borrower_name }}</td>
                <td>{{ $d->total }}</td>
                <td>{{ \Carbon\Carbon::parse($d->lending->date)->format('d M Y') }}</td>
                <td>
                    @if($d->lending->returned_date)
                        <span class="badge bg-success">{{ \Carbon\Carbon::parse($d->lending->returned_date)->format('d M Y') }}</span>
                    @else
                        <span class="badge bg-warning">Not Returned</span>
                    @endif
                </td>
                <td>{{ $d->lending->user->name ?? '-' }}</td>
                <td>{{ $d->lending->editor->name ?? '-' }}</td>
            </tr>
            @endforeach
            @if($details->isEmpty())
            <tr>
                <td colspan="7" class="text-center text-muted">No lending history found for this item.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
