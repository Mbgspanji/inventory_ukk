<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Lending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function export()
    {
        return Excel::download(new ItemsExport, 'items_inventory.xlsx');
    }

    public function index(Request $request)
    {
        $categories = Category::all();

        // Inisialisasi query
        $query = Item::with('category');

        // Logika Filter (dari form Anda)
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $items = $query->latest()->get();

        return view('items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'total' => 'required|integer|min:0'
        ]);

        $data = $request->all();
        $data['available'] = $data['total'];
        $data['lending_total'] = 0;
        $data['repair'] = 0;

        Item::create($data);
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'total' => 'required|integer|min:0',
            'new_broken' => 'nullable|integer|min:0',
            'fixed_items' => 'nullable|integer|min:0'
        ]);

        $item->category_id = $request->category_id;
        $item->name = $request->name;
        $item->total = $request->total;

        // 1. Tambah kerusakan baru (jika input manual di form edit)
        $new_broken = $request->new_broken ?? 0;
        $item->repair += $new_broken;

        // 2. Kurangi stok repair jika ada barang selesai diperbaiki
        $fixed_items = $request->fixed_items ?? 0;
        if ($fixed_items > $item->repair) {
            return back()->with('error', 'Fixed items cannot exceed current repair stock.');
        }
        $item->repair -= $fixed_items;

        // 3. Re-calculate available secara otomatis
        $item->available = $item->total - ($item->lending_total + $item->repair);

        if ($item->available < 0) {
            return back()->with('error', 'Update failed. Stock balance cannot be negative.');
        }

        $item->save();
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function returnItem(Request $request, Lending $lending)
    {
        $request->validate([
            'total_returned' => 'required|integer|min:0',
            'total_damaged' => 'required|integer|min:0',
            'notes_damaged' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            foreach ($lending->details as $detail) {
                $item = Item::lockForUpdate()->find($detail->item_id);

                // Validasi input agar total (bagus + rusak) sesuai dengan yang dipinjam
                $totalInput = $request->total_returned + $request->total_damaged;
                if ($totalInput != $detail->total) {
                    throw new \Exception("Input ({$totalInput}) does not match borrowed quantity ({$detail->total}) for {$item->name}.");
                }

                // 1. Kurangi lending_total (karena barang sudah kembali ke gudang/terdata)
                $item->lending_total -= $detail->total;

                // 2. Tambahkan ke kolom 'repair' (barang rusak langsung masuk database item)
                $item->repair += $request->total_damaged;

                // 3. Update Available: Hanya bertambah dari total_returned yang kondisinya bagus
                $item->available = $item->total - ($item->lending_total + $item->repair);

                $item->save();
            }

            // Update status Lending
            $lending->returned_date = now();
            $lending->edited_by = Auth::id();
            $lending->keterangan = ($lending->keterangan ? $lending->keterangan . " | " : "") .
                "Returned: {$request->total_returned}, Damaged: {$request->total_damaged}. Note: {$request->notes_damaged}";
            $lending->save();

            DB::commit();
            return back()->with('success', 'Return processed. Repair stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Item $item)
    {
        if ($item->lending_total > 0) {
            return back()->with('error', 'Cannot delete item currently being lent.');
        }
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function history(Item $item)
    {
        $details = $item->details()->with(['lending.user', 'lending.editor'])->get();
        return view('items.history', compact('item', 'details'));
    }
}
