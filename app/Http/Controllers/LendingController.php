<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\LendingDetail;
use App\Models\Item;
use App\Exports\LendingsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LendingController extends Controller
{
    public function export()
    {
        return Excel::download(new LendingsExport, 'lendings.xlsx');
    }

    public function index(Request $request)
    {
        $lendings = Lending::with(['user', 'details.item', 'editor'])
            ->when($request->borrower_name, function ($query, $name) {
                return $query->where('borrower_name', 'like', "%{$name}%");
            })
            ->when($request->date, function ($query, $date) {
                return $query->whereDate('date', $date);
            })
            ->when($request->item_id, function ($query, $item_id) {
                return $query->whereHas('details', function ($q) use ($item_id) {
                    $q->where('item_id', $item_id);
                });
            })
            ->latest('updated_at')->get();

        $items = Item::all();
        return view('lendings.index', compact('lendings', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required',
            'items' => 'required|array',
            'items.*' => 'required|exists:items,id',
            'totals' => 'required|array',
            'totals.*' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $lending = Lending::create([
                'user_id' => Auth::id(),
                'borrower_name' => $request->borrower_name,
                'keterangan' => $request->keterangan,
                'date' => now(),
            ]);

            foreach ($request->items as $index => $item_id) {
                $total = $request->totals[$index];
                $item = Item::lockForUpdate()->find($item_id);

                if ($item->available < $total) {
                    throw new \Exception("Stok {$item->name} tidak cukup.");
                }

                LendingDetail::create([
                    'lending_id' => $lending->id,
                    'item_id' => $item_id,
                    'total' => $total
                ]);

                $item->lending_total += $total;
                $item->available -= $total;
                $item->save();
            }

            DB::commit();
            return redirect()->route('lendings.index')->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function returnItem(Request $request, Lending $lending)
    {
        if ($lending->returned_date) {
            return back()->with('error', 'Sudah dikembalikan sebelumnya.');
        }

        $request->validate([
            'returns' => 'required|array',
            'returns.*.good' => 'required|integer|min:0',
            'returns.*.damaged' => 'required|integer|min:0',
            'notes_damaged' => 'nullable|string',
            'signature' => 'required' // Pastikan tanda tangan dikirim dari modal
        ]);

        try {
            DB::beginTransaction();

            $summaryDamaged = 0;

            foreach ($lending->details as $detail) {
                $itemId = $detail->item_id;
                $inputGood = $request->returns[$itemId]['good'] ?? 0;
                $inputDamaged = $request->returns[$itemId]['damaged'] ?? 0;

                if (($inputGood + $inputDamaged) != $detail->total) {
                    $itemName = $detail->item->name ?? 'Item';
                    throw new \Exception("Total kembali {$itemName} tidak sesuai.");
                }

                $item = Item::lockForUpdate()->find($itemId);
                $item->lending_total -= $detail->total;
                $item->available += $inputGood; // Hanya kondisi baik yang masuk stok siap pakai
                $item->save();

                $summaryDamaged += $inputDamaged;
            }

            // Update status dan simpan signature pengembalian
            $catatanLama = $lending->keterangan ? $lending->keterangan . " | " : "";
            
            $lending->update([
                'returned_date' => now(),
                'edited_by' => Auth::id(), // Pastikan kolom ini sesuai di DB (editor_id atau edited_by)
                'signature_return' => $request->signature,
                'keterangan' => $catatanLama . "Total Rusak: " . $summaryDamaged . ". Ket: " . $request->notes_damaged
            ]);

            DB::commit();
            // Berikan session untuk trigger cetak struk otomatis di view
            return back()->with('success', 'Barang berhasil dikembalikan.')->with('print_receipt', $lending->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy(Lending $lending)
    {
        try {
            DB::beginTransaction();

            if (is_null($lending->returned_date)) {
                foreach ($lending->details as $detail) {
                    $item = Item::lockForUpdate()->find($detail->item_id);
                    $item->lending_total -= $detail->total;
                    $item->available += $detail->total;
                    $item->save();
                }
            }

            $lending->delete();
            DB::commit();
            return back()->with('success', 'Catatan dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function downloadReceipt(Lending $lending)
    {
        $lending->load(['user', 'details.item', 'editor']);
        $pdf = Pdf::loadView('lendings.receipt', compact('lending'));
        
        // Ukuran struk thermal (80mm x 210mm)
        $pdf->setPaper([0, 0, 226.77, 600], 'portrait');
        return $pdf->stream('receipt-' . $lending->id . '.pdf');
    }

    public function storeSignature(Request $request, $id)
    {
        $request->validate([
            'signature' => 'required'
        ]);

        $lending = Lending::findOrFail($id);
        $lending->signature_borrower = $request->signature;
        $lending->save();

        return redirect()->route('lendings.index')->with('success', 'Tanda tangan peminjam disimpan.');
    }
}