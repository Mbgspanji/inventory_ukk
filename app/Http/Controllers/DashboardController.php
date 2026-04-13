<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Lending;
use App\Models\User;

class DashboardController extends Controller
{
    public function index() {
    $stats = [
        'categories' => Category::count(),
        'items' => Item::sum('total'),
        'lendings' => Lending::whereNull('returned_date')->count(),
        'users' => User::count(),
    ];

    $allItems = Item::with('category')->latest()->take(10)->get();
    $repairItems = Item::where('repair', '>', 0)->with('category')->get();

    // Data Tambahan: Peminjaman Hari Ini & Status Pengembalian
    $recentLendings = Lending::with(['details.item', 'user'])
        ->whereMonth('date', now()->month) // Bisa diganti ->whereDate('date', now()) untuk harian
        ->latest()
        ->take(10)
        ->get();

    return view('dashboard.index', compact('stats', 'allItems', 'repairItems', 'recentLendings'));
}
}
