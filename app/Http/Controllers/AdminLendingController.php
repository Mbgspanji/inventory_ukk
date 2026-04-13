<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\Item;
use Illuminate\Http\Request;

class AdminLendingController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::all();
        $query = Lending::with(['details.item', 'user', 'editor']);

        // Logika Filter (Sama seperti sebelumnya atau disesuaikan)
        if ($request->borrower_name) {
            $query->where('borrower_name', 'like', '%' . $request->borrower_name . '%');
        }

        $lendings = $query->latest()->get();

        return view('admin.lendings.index', compact('lendings', 'items'));
    }

    public function store(Request $request)
    {
        // Logika simpan data khusus admin
    }

    public function destroy(Lending $lending)
    {
        // Admin bisa menghapus transaksi apa saja
        $lending->delete();
        return back()->with('success', 'Transaction deleted by Admin.');
    }

    public function export()
    {
        // Logika export laporan khusus admin
    }
}
