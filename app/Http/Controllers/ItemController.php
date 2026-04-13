<?php
namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\ItemsExport;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    public function export() {
        return Excel::download(new ItemsExport, 'items_inventory.xlsx');
    }

    public function index(Request $request) {
        $items = Item::with('category')
            ->when($request->category_id, function($query, $category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($request->name, function($query, $name) {
                return $query->where('name', 'like', "%{$name}%");
            })
            ->get();
        $categories = Category::all();
        return view('items.index', compact('items', 'categories'));
    }

    public function create() {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'total' => 'required|integer|min:0'
        ]);
        $data = $request->all();
        $data['available'] = $data['total']; 
        Item::create($data);
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item) {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item) {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'total' => 'required|integer|min:0',
            'new_broken' => 'nullable|integer|min:0'
        ]);
        
        $item->category_id = $request->category_id;
        $item->name = $request->name;
        $item->total = $request->total;
        
        $new_broken = $request->new_broken ?? 0;
        $item->repair += $new_broken;
        
        $item->available = $item->total - ($item->lending_total + $item->repair);
        
        if ($item->available < 0) {
            return back()->with('error', 'Update causes available items to be negative. Check loans and brokens.');
        }

        $item->save();
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item) {
        if ($item->lending_total > 0) {
            return back()->with('error', 'Cannot delete item currently being lent.');
        }
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function history(Item $item) {
        $details = $item->details()->with(['lending.user', 'lending.editor'])->get();
        return view('items.history', compact('item', 'details'));
    }
}
