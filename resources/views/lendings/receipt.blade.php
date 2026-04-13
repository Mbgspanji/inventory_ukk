<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
        }

        .text-center {
            text-align: center;
        }

        .header {
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .footer {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-row td {
            padding: 5px 0;
        }

        .total-section {
            margin-top: 10px;
            border-top: 1px solid #000;
        }

        /* CSS untuk Tanda Tangan */
        .signature-wrapper {
            margin-top: 20px;
            width: 100%;
        }

        .signature-box {
            width: 48%;
            display: inline-block;
            text-align: center;
            vertical-align: top;
        }

        /* Mengatur ukuran gambar tanda tangan agar pas */
        .signature-img {
            max-width: 120px;
            height: 60px;
            object-fit: contain;
            margin: 5px 0;
        }

        .space {
            height: 60px;
        }

        /* Fallback jika tanda tangan kosong */
    </style>
</head>

<body>
    <div class="header text-center">
        <h3>STRUK {{ $lending->returned_date ? 'PENGEMBALIAN' : 'PEMINJAMAN' }}</h3>
        <p>Inventaris Barang App</p>
    </div>

    <table>
        <tr>
            <td width="40%">No. Transaksi</td>
            <td>: #LND-{{ $lending->id }}</td>
        </tr>
        <tr>
            <td>Peminjam</td>
            <td>: {{ $lending->borrower_name }}</td>
        </tr>
        <tr>
            <td>Tgl Pinjam</td>
            <td>: {{ \Carbon\Carbon::parse($lending->date)->format('d/m/Y H:i') }}</td>
        </tr>
        @if ($lending->returned_date)
            <tr>
                <td>Tgl Kembali</td>
                <td>: {{ \Carbon\Carbon::parse($lending->returned_date)->format('d/m/Y H:i') }}</td>
            </tr>
        @endif
    </table>

    <div class="total-section">
        <table>
            <thead>
                <tr style="border-bottom: 1px solid #000">
                    <th align="left">Item</th>
                    <th align="right">Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lending->details as $detail)
                    <tr class="item-row">
                        <td>{{ $detail->item->name }}</td>
                        <td align="right">{{ $detail->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        @if ($lending->keterangan)
            <p>Catatan: {{ $lending->keterangan }}</p>
        @endif
        <p class="text-center">-- Harap simpan struk ini sebagai bukti --</p>
        <p class="text-center">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <div class="signature-wrapper">
        <div class="signature-box">
            <p>Peminjam,</p>
            <div class="space">
            </div>
            <p>( {{ $lending->borrower_name }} )</p>
        </div>

        <div class="signature-box">
            <p>Petugas / Penerima,</p>
            @if ($lending->signature_return)
                <img src="{{ $lending->signature_return }}" style="max-width: 150px; height: 80px;">
            @else
                <div class="space" style="height: 80px;"></div>
            @endif
            <p>( {{ $lending->editor->name ?? auth()->user()->name }} )</p>
        </div>
    </div>
</body>

</html>
