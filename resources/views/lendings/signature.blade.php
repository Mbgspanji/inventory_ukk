@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-center">Tanda Tangan Peminjam</h5>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted small">Silahkan tanda tangan di dalam kotak di bawah ini:</p>
                    
                    <div style="border: 2px dashed #ccc; border-radius: 8px; background: #f9f9f9;">
                        <canvas id="signature-pad" class="signature-pad" width="400" height="200"></canvas>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" id="clear" class="btn btn-outline-danger">
                            <i class="fa-solid fa-eraser"></i> Hapus
                        </button>
                        
                        <form action="{{ route('lendings.store-signature', $lending->id) }}" method="POST" id="signature-form">
                            @csrf
                            <input type="hidden" name="signature" id="signature-value">
                            <button type="submit" id="save" class="btn btn-primary">
                                <i class="fa-solid fa-file-signature"></i> Simpan Tanda Tangan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgba(255, 255, 255, 0)', // Transparan
        penColor: 'rgb(0, 0, 0)' // Warna pena hitam
    });

    // Tombol Hapus
    document.getElementById('clear').addEventListener('click', () => {
        signaturePad.clear();
    });

    // Logika pengiriman form
    const form = document.getElementById('signature-form');
    form.addEventListener('submit', (e) => {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            alert("Silahkan tanda tangan terlebih dahulu.");
        } else {
            // Ambil data base64 dari canvas
            const dataUrl = signaturePad.toDataURL(); 
            // Masukkan ke input hidden
            document.getElementById('signature-value').value = dataUrl;
        }
    });

    // Menyesuaikan ukuran kanvas agar responsif
    window.addEventListener("resize", resizeCanvas);
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }
    resizeCanvas();
</script>

<style>
    .signature-pad {
        max-width: 100%;
        cursor: crosshair;
    }
</style>
@endsection