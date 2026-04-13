@extends('layouts.app')
@section('title', 'Lendings')
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


<!-- Filter Card -->
<div class="card mb-4 border-0 p-3">
    <form action="{{ route('lendings.index') }}" method="GET" id="filterForm">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label text-muted small">Borrower Name</label>
                <input type="text" name="borrower_name" class="form-control bg-dark text-white border-secondary auto-filter" placeholder="Search..." value="{{ request('borrower_name') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small">Item</label>
                <select name="item_id" class="form-select bg-dark text-white border-secondary select2-filter auto-filter">
                    <option value="">All Items</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted small">Date</label>
                <input type="date" name="date" class="form-control bg-dark text-white border-secondary auto-filter" value="{{ request('date') }}">
            </div>
            <div class="col-md-1">
                <a href="{{ route('lendings.index') }}" class="btn btn-secondary w-100" title="Reset Filter"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </div>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Borrower</th>
                <th>Items (Total)</th>
                <th>Keterangan</th>
                <th>Date</th>
                <th>Returned</th>
                <th>Edited By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lendings as $index => $l)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $l->borrower_name }}</td>
                <td>
                    <ul class="mb-0 ps-3">
                        @foreach($l->details as $d)
                            <li>{{ $d->item->name ?? 'Unknown' }} ({{ $d->total }})</li>
                        @endforeach
                    </ul>
                </td>
                <td>{{ $l->keterangan }}</td>
                <td>{{ \Carbon\Carbon::parse($l->date)->format('d M Y') }}</td>
                <td>
                    @if($l->returned_date)
                        <span class="badge bg-success">{{ \Carbon\Carbon::parse($l->returned_date)->format('d M Y') }}</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </td>
                <td>
                    @if($l->returned_date)
                        <div class="small">{{ $l->user->name ?? '-' }}</div>
                        <div class="small"><span class="text-info">{{ $l->editor->name ?? '-' }}</span></div>
                    @else
                        <div class="small"><span class="text-info">{{ $l->user->name ?? '-' }}</span></div>
                    @endif
                </td>
                <td>
                    @if(!$l->returned_date)
                        <form action="{{ route('lendings.return', $l->id) }}" method="POST" class="d-inline" id="return-form-{{ $l->id }}">
                            @csrf
                            <button type="button" class="btn btn-sm btn-success mb-1" onclick="confirmAction('return-form-{{ $l->id }}', 'Confirm Return?', 'Are you sure all items have been returned?', 'question', 'Yes, Return Items')"><i class="fa-solid fa-check"></i> Return</button>
                        </form>
                    @endif
                    <form action="{{ route('lendings.destroy', $l->id) }}" method="POST" class="d-inline" id="delete-form-{{ $l->id }}">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger mb-1" onclick="confirmDelete('delete-form-{{ $l->id }}', 'This will revert stocks if items were not returned!')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Add Lending -->
<div class="modal fade" id="addLendingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Lending Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="stock-error" class="alert alert-danger" style="display:none;">
                    <i class="fa-solid fa-triangle-exclamation"></i> Total item more than available!
                </div>
                <form action="{{ route('lendings.store') }}" method="POST" id="lendingForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Borrower Name</label>
                            <input type="text" name="borrower_name" class="form-control @error('borrower_name') is-invalid @enderror" value="{{ old('borrower_name') }}">
                            @error('borrower_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Keterangan (Optional)</label>
                            <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}">
                        </div>
                    </div>
                    
                    <hr>
                    <h5>Items</h5>
                    <div id="items-container">
                        <div class="row item-row mb-3">
                            <div class="col-md-7">
                                <select name="items[]" class="form-select item-select-modal" required>
                                    <option value="">Choose item...</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" data-avail="{{ $item->available }}">
                                            {{ $item->name }} (Avail: {{ $item->available }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="totals[]" class="form-control item-total" required min="1" placeholder="Qty">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-item" style="display:none;"><i class="fa-solid fa-x"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <button type="button" class="btn btn-info text-white btn-sm" id="add-more"><i class="fa-solid fa-plus"></i> More Items</button>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" id="submit-btn">Submit Transaction</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Template for hidden row -->
<div id="item-template" style="display:none;">
    <div class="row item-row mb-3 mt-2">
        <div class="col-md-7">
            <select name="items[]" class="form-select item-select-template" required>
                <option value="">Choose item...</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" data-avail="{{ $item->available }}">
                        {{ $item->name }} (Avail: {{ $item->available }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="number" name="totals[]" class="form-control item-total" required min="1" placeholder="Qty">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-item"><i class="fa-solid fa-x"></i></button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        function initSelect2(element) {
            $(element).select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#addLendingModal')
            });
        }

        $('.select2-filter').select2({
            theme: 'bootstrap-5'
        });

        // Auto Filtering Logic
        let filterTimer;
        $('.auto-filter').on('input change', function() {
            clearTimeout(filterTimer);
            let isSelect = $(this).is('select') || $(this).is('input[type="date"]');
            
            filterTimer = setTimeout(function() {
                $('#filterForm').submit();
            }, isSelect ? 0 : 500); // Debounce 500ms for text input
        });

        initSelect2('.item-select-modal');

        $('#add-more').click(function() {
            let template = $('#item-template').html();
            let newRow = $(template);
            $('#items-container').append(newRow);
            initSelect2(newRow.find('.item-select-template'));
            newRow.find('.item-select-template').removeClass('item-select-template').addClass('item-select-modal');
        });

        $(document).on('click', '.remove-item', function() {
            $(this).closest('.item-row').remove();
            validateStock();
        });

        function validateStock() {
            let hasError = false;
            $('#stock-error').hide();
            $('#submit-btn').prop('disabled', false);

            $('.item-row').each(function() {
                let select = $(this).find('select');
                let totalInput = $(this).find('.item-total');
                let selectedOption = select.find('option:selected');
                
                if (selectedOption.val() !== "") {
                    let available = parseInt(selectedOption.data('avail'));
                    let total = parseInt(totalInput.val());
                    if (total > available) {
                        hasError = true;
                    }
                }
            });

            if (hasError) {
                $('#stock-error').fadeIn();
                $('#submit-btn').prop('disabled', true);
            }
        }

        $(document).on('change', '.item-select-modal, .item-total', function() {
            validateStock();
        });

        $('#lendingForm').submit(function(e) {
            validateStock();
            if ($('#submit-btn').is(':disabled')) {
                e.preventDefault();
                return false;
            }
        });

        @if($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('addLendingModal'));
            myModal.show();
        @endif
    });
</script>
@endsection
