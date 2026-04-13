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
                    <input type="text" name="borrower_name"
                        class="form-control bg-dark text-white border-secondary auto-filter" placeholder="Search..."
                        value="{{ request('borrower_name') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small">Item</label>
                    <select name="item_id"
                        class="form-select bg-dark text-white border-secondary select2-filter auto-filter">
                        <option value="">All Items</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small">Date</label>
                    <input type="date" name="date"
                        class="form-control bg-dark text-white border-secondary auto-filter" value="{{ request('date') }}">
                </div>
                <div class="col-md-1">
                    <a href="{{ route('lendings.index') }}" class="btn btn-secondary w-100" title="Reset Filter"><i
                            class="fa-solid fa-rotate-left"></i></a>
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
                @foreach ($lendings as $index => $l)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $l->borrower_name }}</td>
                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach ($l->details as $d)
                                    <li data-itemid="{{ $d->item_id }}" data-qty="{{ $d->total }}">
                                        {{ $d->item->name ?? 'Unknown' }} ({{ $d->total }})
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $l->keterangan }}</td>
                        <td>{{ \Carbon\Carbon::parse($l->date)->format('d M Y') }}</td>
                        <td>
                            @if ($l->returned_date)
                                <span
                                    class="badge bg-success">{{ \Carbon\Carbon::parse($l->returned_date)->format('d M Y') }}</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if ($l->returned_date)
                                <div class="small">{{ $l->user->name ?? '-' }}</div>
                                <div class="small"><span class="text-info">{{ $l->editor->name ?? '-' }}</span></div>
                            @else
                                <div class="small"><span class="text-info">{{ $l->user->name ?? '-' }}</span></div>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-link text-dark p-0 border-0 shadow-none" type="button"
                                    id="actionMenu{{ $l->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical fs-5"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border"
                                    aria-labelledby="actionMenu{{ $l->id }}">
                                    {{-- Bagian Header Kecil (Opsional) --}}
                                    <li>
                                        <h6 class="dropdown-header">Actions</h6>
                                    </li>

                                    {{-- Tombol Struk --}}
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('lendings.receipt', $l->id) }}"
                                            target="_blank">
                                            <i class="fa-solid fa-print text-primary me-2"></i> Cetak Struk
                                        </a>
                                    </li>

                                    {{-- Tombol Return --}}
                                    @if (!$l->returned_date)
                                        <li>
                                            <button type="button" class="dropdown-item py-2 open-return-modal"
                                                data-id="{{ $l->id }}" data-borrower="{{ $l->borrower_name }}"
                                                data-bs-toggle="modal" data-bs-target="#returnLendingModal">
                                                <i class="fa-solid fa-check text-success me-2"></i> Kembalikan Barang
                                            </button>
                                        </li>
                                    @endif

                                    {{-- Garis Pembatas --}}
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    {{-- Tombol Hapus --}}
                                    <li>
                                        <form action="{{ route('lendings.destroy', $l->id) }}" method="POST"
                                            id="delete-form-{{ $l->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="dropdown-item py-2 text-danger"
                                                onclick="confirmDelete('delete-form-{{ $l->id }}', 'This will revert stocks if items were not returned!')">
                                                <i class="fa-solid fa-trash me-2"></i> Hapus Catatan
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
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
                                <input type="text" name="borrower_name"
                                    class="form-control @error('borrower_name') is-invalid @enderror"
                                    value="{{ old('borrower_name') }}">
                                @error('borrower_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label>Keterangan (Optional)</label>
                                <input type="text" name="keterangan" class="form-control"
                                    value="{{ old('keterangan') }}">
                            </div>
                        </div>

                        <hr>
                        <h5>Items</h5>
                        <div id="items-container">
                            <div class="row item-row mb-3">
                                <div class="col-md-7">
                                    <select name="items[]" class="form-select item-select-modal" required>
                                        <option value="">Choose item...</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" data-avail="{{ $item->available }}">
                                                {{ $item->name }} (Avail: {{ $item->available }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="totals[]" class="form-control item-total" required
                                        min="1" placeholder="Qty">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-item" style="display:none;"><i
                                            class="fa-solid fa-x"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button type="button" class="btn btn-info text-white btn-sm" id="add-more"><i
                                    class="fa-solid fa-plus"></i> More Items</button>
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

    <div class="modal fade" id="returnLendingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Return Items - <span id="return-borrower-name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="returnLendingForm">
                    @csrf
                    <div class="mb-3 mt-4">
                        <label class="form-label fw-bold d-block">Tanda Tangan Penerima / Peminjam</label>
                        <div style="border: 1px solid #ced4da; border-radius: 5px; background: #f8f9fa;">
                            <canvas id="signature-pad-return" width="500" height="200"
                                style="width: 100%; cursor: crosshair;"></canvas>
                        </div>
                        <input type="hidden" name="signature" id="signature-base64">
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="clear-signature">
                            <i class="fa-solid fa-eraser"></i> Hapus Tanda Tangan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Template for hidden row -->
    <div id="item-template" style="display:none;">
        <div class="row item-row mb-3 mt-2">
            <div class="col-md-7">
                <select name="items[]" class="form-select item-select-template" required>
                    <option value="">Choose item...</option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}" data-avail="{{ $item->available }}">
                            {{ $item->name }} (Avail: {{ $item->available }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="totals[]" class="form-control item-total" required min="1"
                    placeholder="Qty">
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

            // Auto Filtering
            let filterTimer;
            $('.auto-filter').on('input change', function() {
                clearTimeout(filterTimer);
                let isSelect = $(this).is('select') || $(this).is('input[type="date"]');

                filterTimer = setTimeout(function() {
                    $('#filterForm').submit();
                }, isSelect ? 0 : 500);
            });

            initSelect2('.item-select-modal');

            $('#add-more').click(function() {
                let template = $('#item-template').html();
                let newRow = $(template);
                $('#items-container').append(newRow);
                initSelect2(newRow.find('.item-select-template'));
                newRow.find('.item-select-template')
                    .removeClass('item-select-template')
                    .addClass('item-select-modal');
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

            @if ($errors->any())
                var myModal = new bootstrap.Modal(document.getElementById('addLendingModal'));
                myModal.show();
            @endif
        });

        // =========================
        // RETURN MODAL (FIXED)
        // =========================
        $(document).on('click', '.open-return-modal', function() {
            let id = $(this).data('id');
            let borrower = $(this).data('borrower');

            $('#return-borrower-name').text(borrower);

            // FIX route
            let actionUrl = "{{ route('lendings.return', ':id') }}";
            $('#returnLendingForm').attr('action', actionUrl.replace(':id', id));

            let itemsHtml = `
        <table class="table table-bordered align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th>Item Name</th>
                    <th width="150">Good Condition</th>
                    <th width="150">Damaged/Lost</th>
                </tr>
            </thead>
            <tbody>`;

            $(this).closest('tr').find('ul li').each(function() {
                let itemId = $(this).data('itemid');
                let qtyBorrowed = parseInt($(this).data('qty'));

                // ❗ Skip kalau data tidak valid
                if (!itemId || isNaN(qtyBorrowed)) return;

                // Ambil nama item TANPA "(1)"
                let fullText = $(this).text().trim();
                let itemName = fullText.replace(/\(\d+\)/, '').trim();

                itemsHtml += `
    <tr>
        <td>
            <span class="fw-bold">${itemName}</span><br>
            <small class="badge bg-secondary">Total Borrowed: ${qtyBorrowed}</small>
        </td>
        <td>
            <input type="number" name="returns[${itemId}][good]" 
                class="form-control return-qty-input" 
                data-max="${qtyBorrowed}"
                value="${qtyBorrowed}" min="0" max="${qtyBorrowed}" required>
        </td>
        <td>
            <input type="number" name="returns[${itemId}][damaged]" 
                class="form-control return-damaged-input" 
                value="0" min="0" max="${qtyBorrowed}" required>
        </td>
    </tr>`;
            });

            itemsHtml += `</tbody></table>
        <div id="return-validation-msg" class="alert alert-danger d-none">
            <i class="fa-solid fa-circle-exclamation"></i> 
            The sum of good and damaged items must equal the borrowed amount.
        </div>`;

            $('#return-items-list').html(itemsHtml);
            $('#confirm-return-btn').prop('disabled', false);
        });

        // VALIDATION RETURN
        $(document).on('input', '.return-qty-input, .return-damaged-input', function() {
            let row = $(this).closest('tr');
            let good = parseInt(row.find('.return-qty-input').val()) || 0;
            let damaged = parseInt(row.find('.return-damaged-input').val()) || 0;
            let max = parseInt(row.find('.return-qty-input').data('max'));

            if (good + damaged !== max) {
                row.addClass('table-danger');
                $('#confirm-return-btn').prop('disabled', true);
                $('#return-validation-msg').removeClass('d-none');
            } else {
                row.removeClass('table-danger');

                if ($('.table-danger').length === 0) {
                    $('#confirm-return-btn').prop('disabled', false);
                    $('#return-validation-msg').addClass('d-none');
                }
            }
        });

        // SWEET ALERT PRINT
        @if (session('print_receipt'))
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pengembalian dicatat. Cetak struk sekarang?',
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: '<i class="fa fa-print"></i> Cetak Struk',
                cancelButtonText: 'Nanti saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("{{ route('lendings.receipt', session('print_receipt')) }}", '_blank');
                }
            });
        @endif

        // Tambahkan di dalam $(document).ready(function() { ... })

        let signaturePad;
        const canvas = document.getElementById('signature-pad-return');

        // Inisialisasi Signature Pad saat modal dibuka
        $('#returnLendingModal').on('shown.bs.modal', function() {
            // Sesuaikan ukuran canvas agar tidak distorsi
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgb(0, 0, 0)'
            });
        });

        // Tombol Hapus Tanda Tangan
        $('#clear-signature').on('click', function() {
            signaturePad.clear();
        });

        // Masukkan data base64 ke input hidden saat form disubmit
        $('#returnLendingForm').submit(function(e) {
            if (signaturePad.isEmpty()) {
                alert("Silahkan isi tanda tangan terlebih dahulu untuk bukti pengembalian.");
                e.preventDefault();
                return false;
            }

            const dataUrl = signaturePad.toDataURL();
            $('#signature-base64').val(dataUrl);
        });
    </script>
@endsection
