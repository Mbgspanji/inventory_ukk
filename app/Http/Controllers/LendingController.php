<?php
namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\LendingDetail;
use App\Models\Item;
use App\Exports\LendingsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LendingController extends Controller
{
    public function export() {
        return Excel::download(new LendingsExport, 'lendings.xlsx');
    }
    public function index(Request $request) {
        $lendings = Lending::with(['user', 'details.item', 'editor'])
            ->when($request->borrower_name, function($query, $name) {
                return $query->where('borrower_name', 'like', "%{$name}%");
            })
            ->when($request->date, function($query, $date) {
                return $query->whereDate('date', $date);
            })
            ->when($request->item_id, function($query, $item_id) {
                return $query->whereHas('details', function($q) use ($item_id) {
                    $q->where('item_id', $item_id);
                });
            })
            ->latest('updated_at')->get();
        $items = Item::all();
        return view('lendings.index', compact('lendings', 'items'));
    }

    public function create() {
        $items = Item::where('available', '>', 0)->get();
        return view('lendings.create', compact('items'));
    }

    public function store(Request $request) {
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
                    throw new \Exception("Stok {$item->name} tidak cukup. (Available: {$item->available})");
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

    public function returnItem(Lending $lending) {
        if ($lending->returned_date) {
            return back()->with('error', 'Already returned.');
        }

        try {
            DB::beginTransaction();

            foreach ($lending->details as $detail) {
                $item = Item::lockForUpdate()->find($detail->item_id);
                $item->lending_total -= $detail->total;
                $item->available += $detail->total;
                $item->save();
            }

            $lending->returned_date = now();
            $lending->edited_by = Auth::id();
            $lending->save();

            DB::commit();
            return back()->with('success', 'Barang berhasil dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengembalikan: ' . $e->getMessage());
        }
    }

    public function destroy(Lending $lending) {
        try {
            DB::beginTransaction();

            if (is_null($lending->returned_date)) {
                // if deleted before returned, we must restore the stock
                foreach ($lending->details as $detail) {
                    $item = Item::lockForUpdate()->find($detail->item_id);
                    $item->lending_total -= $detail->total;
                    $item->available += $detail->total;
                    $item->save();
                }
            }

            $lending->delete();
            DB::commit();
            return back()->with('success', 'Catatan peminjaman dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
