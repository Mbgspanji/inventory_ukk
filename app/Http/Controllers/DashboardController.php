<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\Lending;
use App\Models\User;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'categories' => Category::count(),
            'items' => Item::sum('total'),
            'lendings' => Lending::whereNull('returned_date')->count(),
            'users' => User::count(),
        ];

        $allItems = Item::with('category')->latest()->take(10)->get();
        $repairItems = Item::where('repair', '>', 0)->with('category')->get();

        // Logika Filter
        $filter = $request->get('filter', 'month'); // Default bulan
        $query = Lending::with(['details.item', 'user']);

        if ($filter == 'day') {
            $query->whereDate('date', now()->today());
        } elseif ($filter == 'week') {
            $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
        } else {
            // Default: Month
            $query->whereMonth('date', now()->month)
                ->whereYear('date', now()->year);
        }

        $recentLendings = $query->latest()->get();

        return view('dashboard.index', compact('stats', 'allItems', 'repairItems', 'recentLendings', 'filter'));
    }
}
